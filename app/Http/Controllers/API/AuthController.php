<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'email|required|unique:users',
            'password' => 'required'
        ]);

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'access_token' => $accessToken], 201);
    }

    public function login(Request $request)
    {
        $email = $request['email'];
        $password = $request['password'];

        $employee = Employee::where('email', $email)->first();

        if($employee) {
            if(Hash::check($password, $employee->password)) {
                $accessToken = $employee->createToken('yukyu')->accessToken;
                $employee->token = $accessToken;
                $this->setMemberSession($employee);
                return response(['status' => 'success', 'user' => $employee, 'token' => $accessToken]);
            } else {
                return response(['status' => 'failure', 'error' => 'Invalid Password']);
            }
        } else {
            return response(['status' => 'failure', 'error' => 'Not exist']);
        }
    }

    public function logout(Request $request) {
        if($this->getMemberSession()) {
            $this->deleteMemberSession();
            return response(['status' => 'success']);
        } else {
            return response(['status' => 'failure', 'msg' => 'Not login']);
        }
    }
}
