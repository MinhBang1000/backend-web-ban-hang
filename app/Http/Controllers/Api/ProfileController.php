<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //...
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)){
            return response()->json(['msg'=>'Not Found This User'],404);
        }
        return response()->json(['user'=>$user],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function updateById(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)){
            return response()->json(['msg'=>'Not Found This User'],404);
        }
        $validator = Validator::make($request->all(),[
            'birthday'=>'date',
            'phone'=>'min:10|max:10',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        if (!empty($request->get('name'))){
            $user->name = $request->get('name');
        }
        if (!empty($request->get('birthday'))){
            $user->birthday = date('Y-m-d H:i:s',strtotime($request->get('birthday')));
        }
        if (!empty($request->get('phone'))){
            $user->phone = $request->get('phone');
        }
        $user->save();
        return response()->json(['user'=>$user],200);
    }

    public function updateAvatar(Request $request, $id){
        $user = User::find($id);
        if (is_null($user)){
            return response()->json(['msg'=>'Not Found This User'],404);
        }
        if ($request->hasFile('avatar')){
            $file = $request->file('avatar');
            $user->avatar = 'images/'.$file->getClientOriginalName();
            $user->save();
            $file->move('images',$file->getClientOriginalName());
            return response()->json(['msg'=>'Upload Success'],200);
        }
        return response()->json(['msg'=>'File Not Found'],404);
    }

    public function updateEmail(Request $request, $id){
        $user = User::find($id);
        if (is_null($user)){
            return response()->json(['msg'=>'Not Found This User'],404);
        }
        $validator = Validator::make($request->all(),[
            'email'=>'required|email|unique:users',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $user->email = $request->get('email');
        $user->save();
        return response()->json(['user'=>$user],200);
    }

    /**
     * Password change
     */
    public function updatePassword(Request $request, $id){
        $user = User::find($id);
        if (is_null($user)){
            return response()->json(['msg'=>'Not Found This User'],404);
        }
        $validator = Validator::make($request->all(),[
            'current_password'=>'required|min:8',
            'password'=>'required|min:8|confirmed',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        if (Hash::check($request->get('current_password'),$user->password)){
            //... 
        }else{
            return response()->json(['msg'=>'Your current password is incorrect!'],401);
        }
        $user->password = Hash::make($request->get('password'));
        $user->save();
        return response()->json(['msg'=>'Change Success'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
