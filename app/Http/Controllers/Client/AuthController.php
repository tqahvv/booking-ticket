<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ActivationMail;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('client.pages.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^(0[0-9]{9})$/',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên',
            'name.min' => 'Họ và tên phải có ít nhất 3 ký tự',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'phone.required' => 'Vui lòng nhập số điện thoại',
            'phone.regex' => 'Số điện thoại không hợp lệ (phải có 10 số và bắt đầu bằng 0)',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Mật khẩu nhập lại không khớp',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if ($existingUser->isPending()) {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Tài khoản đã đăng ký và đang chờ kích hoạt'
                ], 409);
            }

            return response()->json([
                'status' => 'exists',
                'message' => 'Email đã tồn tại'
            ], 409);
        }

        $token = Str::random(64);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 3,
            'activation_token' => $token,
            'activation_token_created_at' => now(),
            'status' => 'pending',
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công!',
        ]);
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')
                ->with('error', 'Link kích hoạt không hợp lệ');
        }

        if (Carbon::parse($user->activation_token_created_at)
            ->addMinutes(15)
            ->isPast()
        ) {

            return redirect()->route('login')
                ->with('error', 'Link kích hoạt đã hết hạn, vui lòng gửi lại email kích hoạt');
        }

        $user->update([
            'status' => 'active',
            'activation_token' => null,
            'activation_token_created_at' => null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Kích hoạt tài khoản thành công');
    }

    public function resendActivation(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email không tồn tại'
            ], 404);
        }

        if ($user->status === 'active') {
            return response()->json([
                'message' => 'Tài khoản đã được kích hoạt'
            ], 409);
        }

        $token = Str::random(64);

        $user->update([
            'activation_token' => $token,
            'activation_token_created_at' => now(),
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        return response()->json([
            'message' => 'Đã gửi lại email kích hoạt'
        ]);
    }

    public function checkActivation(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['active' => false]);
        }

        return response()->json([
            'active' => $user->status === 'active'
        ]);
    }

    public function showLoginForm()
    {
        return view('client.pages.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            /** @var \App\Models\User $user */
            if ($user->isBanned()) {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => ['Tài khoản của bạn đang bị chặn.']]
                ], 403);
            }

            if ($user->isDeleted()) {
                Auth::logout();
                return response()->json([
                    'status' => 'error',
                    'errors' => ['email' => ['Tài khoản này đã bị xóa.']]
                ], 403);
            }

            $request->session()->regenerate();
            return response()->json([
                'status' => 'success',
                'message' => 'Đăng nhập thành công!'
            ]);
        }

        return response()->json([
            'status' => 'error',
            'errors' => ['email' => ['Thông tin đăng nhập không chính xác']]
        ], 422);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
