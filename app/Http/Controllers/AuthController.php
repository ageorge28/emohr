<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if($user)
        {
            return response()->json([
                'status' => true,
                'redirect' => url("/edit/$user->id/$user->mobiletoken") 
            ]);
        }
        else 
        {
            return response()->json([
                'status' => false,
                'error' => 'Invalid email'
            ]);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'email' => 'required|email|unique:users,email'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        else
        {
            User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'mobiletoken' => uniqid()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User registration successful'
            ]);
        }

    }

    public function logout()
    {
            session()->flush();
            Auth::logout();
            return redirect()->route('home');
    }

    public function edit(User $user, $token)
    {
        if($user && $user->mobiletoken == $token)
        {
            Auth::loginUsingId($user->id);
            return view('edit', compact('user'));
        }
        else
        {
            return redirect()->route('home')->with('success', 'Sorry you do not have access');
        }
    }

    public function update(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'avatar' => 'mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        else
        {
            if($file = $request->file('avatar'))
            {
                $avatar = date('YmdHis') . "." . $file->getClientOriginalExtension();
                $file->move(public_path('images'), $avatar);
            }

            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'avatar' => $avatar ?? $user->avatar ?? 'default.png'
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User updation successful'
            ]);
        }
    }

    public function list()
    {
        $users = User::orderBy('firstname', 'asc')->get();
        return view('list', compact('users'));
    }
}
