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

        return DB::transaction(function() use($userInput, $parentInput, $request){
            $photoPath = null;
            $corPath = null;
            $gradePath = null;
            // Handling photo upload
            if ($request->hasFile('photo_url')) {
                //$photoPath = $request->file('photo_url')->store('uploads/photos', 'public');
                $photoFile = $request->file('photo_url');
                $newPhotoName = Str::ulid() . '.' . $photoFile->getClientOriginalExtension();  // Generate unique name
                $photoPath = $photoFile->storeAs('uploads/photos', $newPhotoName, 'public');
                $userInput['photo_url'] = $photoPath;
            }

            // Handling document upload
            if ($request->hasFile('cor_url')) {
                //$corPath = $request->file('cor_url')->store('uploads/documents', 'public');
                $corFile = $request->file('cor_url');
                $newCorName = Str::ulid() . '.' . $corFile->getClientOriginalExtension();  // Generate unique name
                $corPath = $corFile->storeAs('uploads/documents', $newCorName, 'public');
                $userInput['cor_url'] = $corPath;
            }

            if ($request->hasFile('grade_url')) {
                //$gradePath = $request->file('grade_url')->store('uploads/documents', 'public');
                $gradeFile = $request->file('grade_url');
                $newGradeName = Str::ulid() . '.' . $gradeFile->getClientOriginalExtension();  // Generate unique name
                $corPath = $gradeFile->storeAs('uploads/documents', $newGradeName, 'public');
                $userInput['grade_url'] = $corPath;
            }

            $user = User::create($userInput);
            $user->parents()->updateOrCreate($parentInput);
            $success['token'] =  $user->createToken(request()->ip())->accessToken;
            $success['name'] =  $user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name;

            /*if ($this->isMobileOnlyDomain($user->email)) {
                // Only verify mobile number
                $user->sendMobileNumberVerificationNotification();
                return response()->json(['message' => 'User registered. OTP sent via SMS for mobile verification.']);
            } else {
                // Verify email
                $user->sendEmailVerificationNotification();
                return response()->json(['message' => 'User registered. Verification link sent to your email.']);
            }*/
            $user->sendEmailVerificationNotification();
            return response()->json(['message' => 'User registered. Verification link sent to your email.']);
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

    public function logout(Request $request)
    {
        /*
         * This will log the user out from the current device where he requested to log out.
         */
        $user = Auth::user()->token();
        $user->revoke();

        return response()->json([
            'status_code' => 200,
            'message' => 'You have successfully logged out!',
        ]);
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

    public function deactivate()
    {
        $user = Auth::user();
        $user->update(['is_active' => 0]);
        $user->token()->revoke();

        return response()->json([
            'status_code' => 200,
            'message' => 'Your account is now deactivated!',
        ]);
    }
}
