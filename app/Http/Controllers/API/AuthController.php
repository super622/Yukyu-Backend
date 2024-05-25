<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class AuthController extends Controller
{
    public function regist(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => 'email|required|unique:tbl_employee',
            'password' => 'required',
            'confirm_password' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['status' => 'failure', 'msg' => '必須情報を正確に入力してください。']);
        }

        if(strlen($request->password) < 6 || strlen($request->confirm_password) < 6) {
            return response(['status' => 'failure', 'msg' => 'パスワードは6文字以上で入力してください。']);
        }

        if($request->password != $request->confirm_password) {
            return response(['status' => 'failure', 'msg' => 'パスワードが一致しません']);
        }

        $data['password'] = Hash::make($request->password);
        $user = Employee::create($data);
        $accessToken = $user->createToken('accessToken')->accessToken;

        $transport = new Swift_SmtpTransport('smtp.example.org', 25);
        $mailer = new Swift_Mailer($transport);

        $from_name  = html_entity_decode('Online-Anytime', ENT_QUOTES);
        $from_email = 'no-reply@online-anytime.com.au';
        $subject = 'Login';
        $email = 'masonrose622@gmail.com';

        $email_content = <<< EOT
            Hello <br />
            Thank you,<br/>
            <img src="https://online-anytime.com.au/olat/images/logo.png" width="150" height="80"><br/>
            Email: support@online-anytime.com
            <br />
        EOT;

        $message = (new Swift_Message($subject))
            ->setFrom([$from_email => $from_name])
            ->setTo([$email])
            ->setBody($email_content, 'text/html');

        $mailer->send($message);

        return response(['status' => 'success', 'user' => $user, 'token' => $accessToken]);
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
                return response(['status' => 'failure', 'msg' => 'パスワードが正しくありません。']);
            }
        } else {
            return response(['status' => 'failure', 'msg' => 'ユーザー情報が存在しません。']);
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
