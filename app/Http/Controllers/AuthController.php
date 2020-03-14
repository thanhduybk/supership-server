<?php


namespace App\Http\Controllers;


use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $user = $request->user();

            $oauthToken = $user->createToken('Supership');

            $accessToken = $oauthToken->accessToken;
            $token = $oauthToken->token;

            if ($request->remember) {
                $token->expires_at = Carbon::now()->addWeek();
            }

            $token->save();

            $success['token'] = $accessToken;
            $success['tokenType'] = 'Bearer';
            $success['name'] = $user->name;

            return $this->sendResponse('Login successfully', $success, 200);
        } else {
            $this->sendError('Invalid email or password', ['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'confirmPassword' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $success['token'] = $user->createToken('Supership')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse('Registered successfully', $success, 201);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();

        return $this->sendResponse('Logout successfully', null, 200);
    }

    public function me(Request $request) {
        Log::info($request->user());
        return $this->sendResponse('Get user info successfully', $request->user(), 200);
    }
}
