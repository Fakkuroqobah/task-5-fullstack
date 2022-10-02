<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public function login()
    {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            $data['status'] = true;
            $data['token'] = 'Bearer ' . $user->createToken('rakamin')->accessToken;

            return $this->res(200, 'Berhasil', $data);
        }else{
            $data['status'] = false;

            return $this->res(401, 'Unauthorized', $data);
        }

    }

    public function profile()
    {
        $user = Auth::user();
        $user = $user->makeHidden(['email_verified_at','password','remember_token']);

        $data['status'] = true;
        $data['user'] = $user;

        return $this->res(200, 'Berhasil', $data);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        $data['status'] = true;

        return $this->res(200, 'Berhasil', $data);
    }
}
