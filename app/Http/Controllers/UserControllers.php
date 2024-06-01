<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserControllers extends Controller
{
    public function index(Request $request){
        // $users = User::where('name', 'like', '%' . request('name') . '%')->orderBy('created_at', 'desc')->paginate(9);

        $users = User::when($request->keyword, function ($query) use ($request) {
            $query->where('name', 'like', "%{$request->keyword}%")
                ->orWhere('email', 'like', "%{$request->keyword}%")
                ->orWhere('phone', 'like', "%{$request->keyword}%");
        })->orderBy('id', 'desc')->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    public function create(){
        return view('pages.users.create');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('success', 'User created succesfully');
    }

    public function edit(User $user){
        return view('pages.users.edit', compact('user'));
    }

    // update
    public function update(Request $request, User $user){

        // Juga melakukan validasi untuk yang terdaftar dan tidak sama dengan id user yang akan diedit maka akan di validasi
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);


        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
        ]);


        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User updated succesfully');
    }

    public function destroy(User $user){
        $user->delete();
           return redirect()->route('users.index')->with('success', 'User deleted succesfully');
    }
}
