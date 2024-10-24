<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{

    protected $mobileOnlyDomains = [
        '@student.tsu.edu.ph',
        '@tarlac.sti.edu.ph',
        '@cldhei.edu.ph',
        '@pwutarlac.edu.ph'
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        /*if (is_null(Auth::user()->email_verified_at)) {
            event(new Registered(Auth::user()));
            throw ValidationException::withMessages([
                'account_status' => 'Your email address is not verified. You need to confirm your account. We have sent you an activation code, please check your email.',
            ]);
        }*/
        $user = auth()->user();
        if ($this->isMobileOnlyDomain($user->email)) {
            if (is_null($user->mobile_verified_at)) {
                // Send OTP if not already sent or expired
                if ($this->isOtpExpired($user)) {
                    // Send OTP to user's mobile number
                    //event(new OtpRequested($user));
                    $user->sendMobileNumberVerificationNotification();
                    throw ValidationException::withMessages([
                        'account_status' => 'Your mobile number is not verified. We have sent you an OTP, please check your SMS.',
                    ]);
                }

                throw ValidationException::withMessages([
                    'account_status' => 'OTP already sent. Please check your SMS.',
                ]);
            }
        } else {
            if (is_null($user->email_verified_at)) {
                // Send email verification if not already sent or expired
                event(new Registered(Auth::user()));
                throw ValidationException::withMessages([
                    'account_status' => 'Your email address is not verified. You need to confirm your account. We have sent you an activation code, please check your email.',
                ]);
            }
        }

        if (Auth::user()->is_active == 0) {
            throw ValidationException::withMessages([
                'account_status' => 'Account not activated!',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }

    protected function isMobileOnlyDomain($emailDomain)
    {
        foreach ($this->mobileOnlyDomains as $domain) {
            if (Str::endsWith($emailDomain, $domain)) {
                return true;
            }
        }
        return false;
    }

    // Check if OTP is expired
    protected function isOtpExpired($user)
    {
        // Assuming you store otp_sent_at timestamp in the user or a separate table
        $otpValidDuration = now()->subMinutes(5);  // OTP valid for 5 minutes
        return $user->otp->expires_at->lessThan($otpValidDuration);
    }
}
