<?php

namespace App\Http\Controllers\AuthApi;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{

    public function login()
    {
        if(Auth::attempt(['name' => request('name'), 'password' => request('password')])){
            /**
             * @var User $user
             */
            $user = Auth::user();

            $token =  $user->createToken('Laravel Password Grant Client')->accessToken;
            return response()->json(['success' => [
                'bearer_token' => $token
            ]], 200);
        } else {
            return response()->json(['error'=>'Unauthorized'], 401);
        }
    }
}
