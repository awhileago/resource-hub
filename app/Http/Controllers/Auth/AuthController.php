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
use Illuminate\Validation\Rules\Password;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return JsonResponse
     */
    public function register(UserRequest $request): JsonResponse
    {
        $userInput = $request->safe()->except(['fathers_name','fathers_occupation','fathers_company','mothers_name','mothers_occupation','mothers_company','average_monthly_income']);
        $parentInput = $request->safe()->only(['fathers_name','fathers_occupation','fathers_company','mothers_name','mothers_occupation','mothers_company','average_monthly_income']);
        //$input['password'] = bcrypt($input['password']);
        return DB::transaction(function() use($userInput, $parentInput){
            $user = User::create($userInput);
            $user->parents()->updateOrCreate($parentInput);
            $success['token'] =  $user->createToken(request()->ip())->accessToken;
            $success['name'] =  $user->last_name . ', ' . $user->first_name . ' ' . $user->middle_name;

            return $this->sendResponse($success, 'User register successfully.');
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
            $success['name'] =  $user->name;

            return $this->sendResponse($success, 'User login successfully.');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }
}
