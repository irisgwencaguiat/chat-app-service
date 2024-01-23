<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\SignUpRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function signUp(SignUpRequest $request) {
        $data = $request->validated();

        $user = User::create($data)->fresh();

        $credentials = [
            "email" => $request->get("email"),
            "password" => $request->get("password"),
        ];

        if (!Auth::attempt($credentials)) {
            return customResponse()
                ->data([])
                ->message("Failed to sign up.")
                ->unathorized()
                ->generate();
        }

        $accessToken = Auth::user()->createToken("authToken")->accessToken;

        return customResponse()
            ->data(['user' => $user,
                'access_token' => $accessToken])
            ->message('Successfully created record')
            ->success()
            ->generate();
    }

    public function login(Request $request) {
        $credentials = [
            "email" => $request->get("email"),
            "password" => $request->get("password"),
        ];

        if (!Auth::attempt($credentials)) {
            $userExist = User::where('email', $request->get("email"))->get()->first();
            if (is_null($userExist)) {
                return customResponse()
                    ->data([])
                    ->message("Account doesn’t exist. Please create an account before logging in.")
                    ->unathorized()
                    ->generate();
            }
            return customResponse()
                ->data([])
                ->message("Invalid credentials.")
                ->unathorized()
                ->generate();
        }

        $accessToken = Auth::user()->createToken("authToken")->accessToken;

        $user = User::find(Auth::id());

        return customResponse()
            ->data(['user' => $user->load('userRooms'),
                'access_token' => $accessToken])
            ->message('Successfully logged in.')
            ->success()
            ->generate();
    }

    public function logout() {
        Auth::logout();

        return customResponse()
            ->data([])
            ->message('Successfully logged out.')
            ->success()
            ->generate();
    }
}
