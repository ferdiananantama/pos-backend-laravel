<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request){
        $users = DB::table('users')->when($request -> input('name'), function($query, $name)
        {
            $query->where('name', 'like', '%' .$name. '%')
            ->orwhere('email', 'like', '%'.$name. '%');
        }
        )->paginate(8);
        return view('page.user.index', compact('users'));
    }

    public function create(){
        return view('page.user.create');
    }


    public function store(Request $request){
        //add new user...
        $data = $request->all();
        $data['password']=Hash::make($request -> input('password'));
        User::create($data);
        return redirect() -> route('users.index');
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('page.user.edit', compact('user'));
    }


    public function update(Request $request, $id){
        //update
        $request -> validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required|in:admin, staff, user',
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

        return redirect()-> route('users.index')->with('succes', 'User berhasil diedit');
    }

    public function destroy($id){
        //delete request...
        $users = User::find($id);
        $users->delete();

        return redirect()->route('users.index')->with('succes', 'User berhasil dihapus');
    }
}
