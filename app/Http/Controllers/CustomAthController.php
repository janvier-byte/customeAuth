<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;
class CustomAthController extends Controller
{
    function login()
    {
        return view("auth.login");
    }
    function registration()
    {
        return view("auth.registration");
    }
    function registerUser(Request $request)
    { 
        $request  ->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required'
        ]);
        $user= new User();
        $user->name=$request->name;
        $user->email=$request->email;
        $user->password=Hash::make($request->password);
        $res=$user->save();
        if($res)
        { 
            return back()->with('Success','You are Registered Successfuly');
        }else{
            return back()->with('Fail','Some Thing Went Wrong');

        }
    }
   public function loginUser(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        $user= User::where('email','=',$request->email)->first();
        if($user)
        {
            if(Hash::check($request->password, $user->password)){
                $request->session()->put('loginId', $user->id);
                return redirect('dashbord');
            }else{
                return back()->with('Fail','Password not Matches.'); 
            }
        }else{
            return back()->with('Fail','This email is not registered');
        }
    } 
    public function dashbord()
    {
        return view("Admin.dashboard"); 
    }
}
