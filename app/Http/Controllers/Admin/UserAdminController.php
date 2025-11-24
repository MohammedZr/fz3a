<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserAdminController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id', 'DESC')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function makeAdmin(User $user)
    {
        $user->role = 'admin';
        $user->save();

        return back()->with('success', 'تم ترقية المستخدم إلى مسؤول بنجاح');
    }

    public function removeAdmin(User $user)
    {
        $user->role = 'user';
        $user->save();

        return back()->with('success', 'تم إزالة صلاحيات المسؤول من المستخدم');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', 'تم حذف المستخدم بنجاح');
    }
}
