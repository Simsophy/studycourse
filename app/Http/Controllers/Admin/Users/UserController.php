<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // List all users
    public function index()
    {
        Gate::authorize('manage-users');

        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Show create form
    public function create()
    {
        Gate::authorize('manage-users');

        return view('admin.users.create');
    }

    // Store new user
    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6',
            'can_view_content' => 'nullable|boolean',
            'can_save_content' => 'nullable|boolean',
            'can_download_content' => 'nullable|boolean',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>bcrypt($request->password),
            'can_view_content' => $request->boolean('can_view_content'),
            'can_save_content' => $request->boolean('can_save_content'),
            'can_download_content' => $request->boolean('can_download_content'),
        ]);

        return redirect()->route('admin.users.index');
    }

    // Edit user
    public function edit(User $user)
    {
        Gate::authorize('manage-users');

        return view('admin.users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'name'=>'required',
            'email'=>"required|email|unique:users,email,$user->id",
            'can_view_content' => 'nullable|boolean',
            'can_save_content' => 'nullable|boolean',
            'can_download_content' => 'nullable|boolean',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'can_view_content' => $request->boolean('can_view_content'),
            'can_save_content' => $request->boolean('can_save_content'),
            'can_download_content' => $request->boolean('can_download_content'),
        ]);

        if($request->password){
            $user->password = bcrypt($request->password);
            $user->save();
        }

        return redirect()->route('admin.users.index');
    }

    // Delete user
    public function destroy(User $user)
    {
        Gate::authorize('manage-users');

        $user->delete();
        return redirect()->route('admin.users.index');
    }
}