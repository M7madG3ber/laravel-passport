<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        $client = new Client([
            'verify' => false
        ]);

        try {
            return $client->post(config('services.PASSPORT_ENDPOINT'), [
                'form_params' => [
                    'client_secret' => config('services.PASSPORT_CLIENT_ID'),
                    'grant_type' => config('services.PASSPORT_GRANT_TYPE'),
                    'client_id' => 2,
                    'username' => $request->email,
                    'password' => $request->password,
                ]
            ]);
        } catch (BadResponseException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        $data['password'] = bcrypt($request->password);

        $user = User::create($data);

        return $this->login($request);
    }

    public function logout()
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'status' => 'sucess',
            'message' => "Logout successfuly"
        ]);
    }
}