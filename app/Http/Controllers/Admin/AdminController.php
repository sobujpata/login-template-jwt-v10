<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Admin;
use App\Helper\JWTToken;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    function LoginPage():View{
        return view('pages.auth.login-page');
    }
    function ProfilePage():View{
        return view('pages.dashboard.profile-page');
    }

    public function userLogin(Request $request)
    {
        $count = Admin::where('email', '=', $request->input('email'))
                ->where('password', '=', $request->input('password'))
                ->select('id')
                ->first();

                if($count !== null){
                    //user login -> JWT token issue
                    $token = JWTToken::CreateToken($request->input('email'), $count->id);
                    return response()->json([
                        'status'=>'success',
                        'message'=>'Login successfully',
                        // 'token'=>$token
                    ], 200)->cookie('token',$token,time()+60*24*30);
                }else{
                    return response()->json([
                        'status'=>'failed',
                        'message'=>'unauthorized',
                    ], 201);
                }

    }
    function UserProfile(Request $request){
        $email=$request->header('email');
        $user=Admin::where('email','=',$email)->first();
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
            Admin::where('email','=',$email)->update([
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

