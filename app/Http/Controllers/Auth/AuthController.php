<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * REGISTER NEW ACCOUNT BY API
     */
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            // RULE TO VALIDATION
            'email'=>'required|email|unique:users',
            'password'=>'required|min:8|confirmed',
            'name'=>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $password = Hash::make($request->get('password'));
        //CREATE ACCOUNT CART -> EMPTY CART
        $users = User::create([
            'email'=>$request->get('email'),
            'password'=>$password,
            'name'=>$request->get('name'),
            'avatar'=>null,
            'birthday'=>null,
            'phone'=>empty($request->get('phone'))?$request->get('phone'):null,
            'role_id'=>1,
            'cart_id'=>null
        ]);
        $cart = Cart::create([
            'user_id'=>$users->id,
        ]);
        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $path = 'images/'.$file->getClientOriginalName();
            $file->move('images',$file->getClientOriginalName());
            $users->avatar = $path;
        }
        if (!empty($request->get('birthday'))){
            $users->birthday = date('Y-m-d H:i:s',strtotime($request->get('birthday')));
        }
        $users->cart_id = $cart->id;
        $users->save();
        $users->role;
        return response()->json(['msg'=>'Register Success','user'=>$users],200);
    }

    /**
     * LOGIN API
     */
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|min:8',
        ]);
        if ($validator->fails()){
            return response(['msg'=>$validator->errors()],400);
        }
        if (Auth::attempt(['email'=>$request->get('email'),'password'=>$request->get('password')])){
            $token = Auth::user()->createToken('bl_shop')->accessToken;
            $user = Auth::user();
            return response()->json(['msg'=>'Login Success','user'=>$user,'acess_token'=>$token],200);
        }
        return response()->json(['msg'=>'Login Fail'],401);
    }
}
