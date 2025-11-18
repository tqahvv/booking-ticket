<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $roleName = $request->query('role');

        if ($roleName) {
            $users = User::whereHas('role', function ($q) use ($roleName) {
                $q->where('name', $roleName);
            })->with('role')->paginate(9)->withQueryString();
        } else {
            $users = User::with('role')->paginate(9)->withQueryString();
        }

        $counts = [
            'all' => User::count(),
            'admin' => User::whereHas('role', fn($q) => $q->where('name', 'admin'))->count(),
            'staff' => User::whereHas('role', fn($q) => $q->where('name', 'staff'))->count(),
            'customer' => User::whereHas('role', fn($q) => $q->where('name', 'customer'))->count(),
        ];

        if ($request->ajax()) {
            return view('admin.components.users-list', compact('users'))->render();
        }

        return view('admin.pages.users', compact('users', 'counts'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|in:active,banned,deleted'
        ]);

        $targetUser = User::findOrFail($request->user_id);
        $currentUser = Auth::user();

        $currentRole = $currentUser->role->name;
        $targetRole = $targetUser->role->name;

        if ($currentUser->id === $targetUser->id) {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không thể tự thay đổi trạng thái của chính mình.'
            ], 403);
        }

        if ($currentRole === 'staff' && $targetRole !== 'customer') {
            return response()->json([
                'status' => false,
                'message' => 'Bạn không có quyền thực hiện thao tác này.'
            ], 403);
        }

        if ($currentRole === 'admin' && $targetRole === 'admin') {
            return response()->json([
                'status' => false,
                'message' => 'Admin không được thay đổi trạng thái của admin khác.'
            ], 403);
        }

        $targetUser->status = $request->status;
        $targetUser->save();

        return response()->json([
            'status' => true,
            'message' => 'Cập nhật trạng thái thành công.'
        ]);
    }
}
