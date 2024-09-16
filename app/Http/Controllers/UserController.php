<?php

namespace App\Http\Controllers;

use App\Mail\OTPMail;
use Exception;
use Illuminate\Http\Request;
use App\Helper\JWTToken;
use App\Models\User;
use Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    function LoginPage():View{
        return view('pages.auth.login-page');
    }

    function RegistrationPage():View{
        return view('pages.auth.registration-page');
    }
    function SendOtpPage():View{
        return view('pages.auth.send-otp-page');
    }
    function VerifyOTPPage():View{
        return view('pages.auth.verify-otp-page');
    }

    function ResetPasswordPage():View{
        return view('pages.auth.reset-pass-page');
    }

    function ProfilePage():View{
        return view('pages.dashboard.profile-page');
    }
    public function userLogin(Request $request)
    {
        $count = User::where('email', '=', $request->input('email'))
        ->where('password', '=', $request->input('password'))
        ->select('id', 'role')
        ->first();

        if($count !== null){
            //user login -> JWT token issue
            $token = JWTToken::CreateToken($request->input('email'), $count->id, $count->role);
            return response()->json([
                'status'=>'success',
                'message'=>'Login successfully',
                // 'token'=>$token
                'data'=>$count->role
            ], 200)->cookie('token',$token,time()+60*24*30);
                }else{
                    return response()->json([
                        'status'=>'failed',
                        'message'=>'unauthorized',
                    ], 201);
                }

    }
    function UserRegistration(Request $request){
        try {
            User::create([
                'firstName' => $request->input('firstName'),
                'lastName' => $request->input('lastName'),
                'email' => $request->input('email'),
                'mobile' => $request->input('mobile'),
                'password' => $request->input('password'),
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'User Registration Successfully'
            ],200);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'User Registration Failed'
            ],200);

        }
    }

    public function SendOTPCode(Request $request)
    {
        $email = $request->input('email');

        $otp = rand(100000, 999999);
        $count = User::where('email', '=', $email)->count();

        if($count == 1){
            Mail::to($email)->send(new OTPMail($otp));

            User::where('email', '=', $email)->update(['otp'=>$otp]);

            return response()->json([
                'status'=>'success',
                'message'=>'4 degite OTP send your Email'
        ]);
        }else{
            return response()->json([
                'status'=>'fsiled',
                'message'=>'unauthorized'
            ]);
        }
        
    }

    public function VerifyOTP(Request $request)
    {
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)
                ->where('otp', '=', $otp)
                ->count();

            if($count==1){
                //Database update otp
                User::where('email', '=', $email)->update(['otp'=>'0']);

                //Pass reset token issue
                $token = JWTToken::CreateTokenForPassword($request->input('email'));

                return response()->json([
                    'status'=>'success',
                    'message'=>'OTP Verified',
                    'token'=>$token
                ], 200)->cookie('token',$token,60*24*30);
            }else{
                return response()->json([
                    'status'=>'fsiled',
                    'message'=>'unauthorized'
                ], 401);
            }
    }

    public function ResetPassword(Request $request)
    {
        try{
            $email = $request->header('email');
            $password = $request->input('password');

            User::where('email', '=', $email)
                ->update(['password' => $password]);

                return response()->json([
                    'status'=>'success',
                    'message'=>'Password reset successfully'
                ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>'fsiled',
                'message'=>'Password reset failed'
            ]);
        }
    }
    public function UserLogout(){
        return redirect('/')->cookie('token', '', -1);
    }
    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=User::where('email','=',$email)->first();
        return response()->json([
            'status' => 'success',
            'message' => 'Request Successful',
            'data' => $user
        ],200);
    }

    function UpdateProfile(Request $request){
        try{
            $email=$request->header('email');
            $firstName=$request->input('firstName');
            $lastName=$request->input('lastName');
            $mobile=$request->input('mobile');
            $password=$request->input('password');
            User::where('email','=',$email)->update([
                'firstName'=>$firstName,
                'lastName'=>$lastName,
                'mobile'=>$mobile,
                'password'=>$password
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Request Successful',
            ],200);

        }catch (Exception $exception){
            return response()->json([
                'status' => 'fail',
                'message' => 'Something Went Wrong',
            ],200);
        }
    }

}

