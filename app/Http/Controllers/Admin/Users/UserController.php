<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
        ]);

        return redirect()->route('admin.users.index');
    }

    // Edit user
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'=>'required',
            'email'=>"required|email|unique:users,email,$user->id",
        ]);

        $user->update($request->only('name','email'));

        if($request->password){
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->route('admin.users.index');
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index');
    }
}