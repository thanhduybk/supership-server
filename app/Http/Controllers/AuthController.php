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
        $isEmail = $this->isEmail($request->get('emailOrPhone'));

        $request->validate([
            'emailOrPhone' => !$isEmail ? 'required|digits_between:9,11' : 'required|email',
            'password' => 'required|string',
            'remember' => 'boolean'
        ]);

        $principal = $request->get('emailOrPhone');
        $password = $request->get('password');

        if (filter_var($principal, FILTER_VALIDATE_EMAIL)) {
            Auth::attempt([
                'email' => $principal,
                'password' => $password
            ]);
        } else {
            Auth::attempt([
                'phone' => $principal,
                'password' => $password
            ]);
        }

        if (Auth::check()) {
            $user = $request->user();

            $oauthToken = $user->createToken('Supership');

            $accessToken = $oauthToken->accessToken;
            $token = $oauthToken->token;

            if ($request->remember) {
                $token->expires_at = Carbon::now()->addWeeks(2);
            } else {
                $token->expires_at = Carbon::now()->addWeeks(1);
            }

            $token->save();

            $success['token'] = $accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse('Login successfully', $success, 200);
        } else {
            return $this->sendError(addslashes('Invalid email/phone or password'), ['error' => 'Unauthorized'], 401);
        }
    }

    public function register(Request $request)
    {
        Log::alert($request);
        $validator = Validator::make($request->all(), [
            'shop' => 'required|string',
            'name' => 'required|string',
            'phone' => 'required|digits_between:9,11',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);

            $user = User::create($input);

            $success['token'] = $user->createToken('Supership')->accessToken;
            $success['name'] = $user->name;

            return $this->sendResponse('Registered successfully', null, 201);
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

    private function isEmail(string $str): bool {
        return filter_var($str, FILTER_VALIDATE_EMAIL);
    }
}
