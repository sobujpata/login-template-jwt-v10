<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Middleware\TokenVerificationMiddleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('Home');
});
//Login API

Route::post('/user-login', [UserController::class, 'userLogin'])->name('login');
Route::post('/user-registration', [UserController::class, 'UserRegistration']);
Route::post('/send-otp', [UserController::class, 'SendOTPCode']);
Route::post('/verify-otp', [UserController::class, 'VerifyOTP']);
Route::post('/reset-password', [UserController::class, 'ResetPassword'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/user-profile',[UserController::class,'UserProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/user-update',[UserController::class,'UpdateProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/logout', [UserController::class, 'UserLogout']);

//user login Route
// Route::get('/',[HomeController::class,'HomePage']);
Route::get('/userLogin',[UserController::class,'LoginPage']);
Route::get('/userRegistration',[UserController::class,'RegistrationPage']);
Route::get('/sendOtp',[UserController::class,'SendOtpPage']);
Route::get('/verifyOtp',[UserController::class,'VerifyOTPPage']);
Route::get('/resetPassword',[UserController::class,'ResetPasswordPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/dashboard',[DashboardController::class,'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/userProfile',[UserController::class,'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);

//Admin API
Route::post('/wp-admin/admin-login', [AdminController::class, 'userLogin']);
Route::get('/wp-admin/user-profile',[AdminController::class,'UserProfile'])->middleware([TokenVerificationMiddleware::class]);
Route::post('/wp-admin/user-update',[AdminController::class,'UpdateProfile'])->middleware([TokenVerificationMiddleware::class]);

//admin Route
Route::get('/wp-admin', [AdminController::class, 'LoginPage']);
Route::get('/wp-admin/userProfile', [AdminController::class, 'ProfilePage'])->middleware([TokenVerificationMiddleware::class]);
Route::get('/wp-admin/dashboard', [AdminDashboardController::class, 'DashboardPage'])->middleware([TokenVerificationMiddleware::class]);
