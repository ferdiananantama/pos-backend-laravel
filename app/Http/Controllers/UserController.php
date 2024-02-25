<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //get all users
    public function index(Request $request){
        $users = DB::table('users')->when($request -> input('name'), function($query, $name)
        {
            $query->where('name', 'like', '%' .$name. '%')
            ->orwhere('email', 'like', '%'.$name. '%');
        }
        )->paginate(8);
        return view('page.user.index', compact('users'));
    }

    //link to create page
    public function create(){
        return view('page.user.create');
    }


    //add new user...
    public function store(Request $request){
        $request -> validate([
        'name' => 'required',
        'email' => 'required|unique:users',
        'password' => 'required|min:8',
        'role' => 'required',
        ]);

        $user = new User;
        $user -> name = $request -> name;
        $user -> email = $request -> email;
        $user -> password = Hash::make($request -> password);
        $user -> role = $request -> role;
        $user -> save();

        return redirect()-> route('users.index')->with('success', 'User berhasil dibuat');
    }

    //link to edit page by id
    public function edit($id){
        $user = User::findOrFail($id);
        return view('page.user.edit', compact('user'));
    }


    //update
    public function update(Request $request, $id){
        $request -> validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);

        //update request
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        //if password not empty
        if ($request->password) {
            $user -> password = Hash::make($request->password);
            $user -> save();
        }

        return redirect()-> route('users.index')->with('success', 'User berhasil diedit');
    }

    //delete
    public function destroy($id){
        //delete request...
        $users = User::find($id);
        $users->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
