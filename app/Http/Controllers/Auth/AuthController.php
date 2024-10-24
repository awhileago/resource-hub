<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController as BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Info\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends BaseController
{
    protected $mobileOnlyDomains = [
        '@student.tsu.edu.ph',
        '@tarlac.sti.edu.ph',
        '@cldhei.edu.ph',
        '@pwutarlac.edu.ph'
    ];


    /**
     * Register api
     *
     * @return JsonResponse
     */
    public function register(UserRequest $request): JsonResponse
    {
        $userInput = $request->safe()->except(['fathers_name','fathers_occupation','fathers_company','mothers_name','mothers_occupation','mothers_company','average_monthly_income']);
        $parentInput = $request->safe()->only(['fathers_name','fathers_occupation','fathers_company','mothers_name','mothers_occupation','mothers_company','average_monthly_income']);

        return DB::transaction(function() use($userInput, $parentInput){
            $user = User::create($userInput);
            $user->parents()->updateOrCreate($parentInput);
            $success['token'] =  $user->createToken(request()->ip())->accessToken;
            $success['name'] =  $user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name;

            if ($this->isMobileOnlyDomain($user->email)) {
                // Only verify mobile number
                $user->sendMobileNumberVerificationNotification();
                return response()->json(['message' => 'User registered. OTP sent via SMS for mobile verification.']);
            } else {
                // Verify email
                $user->sendEmailVerificationNotification();
                return response()->json(['message' => 'User registered. Verification link sent to your email.']);
            }
            //return $this->sendResponse($success, 'User register successfully.');
        });
    }

    /**
     * Login api
     *
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user();
            $success['token'] =  $user->createToken(request()->ip())-> accessToken;
            $success['user'] =  $user;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
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
}
