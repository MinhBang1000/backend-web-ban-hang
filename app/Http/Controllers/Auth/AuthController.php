<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Resources\User as UserResource;
use App\Models\Cart;
use App\Models\OauthAccessToken;
use App\Models\User;
use Exception;
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
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // RULE TO VALIDATION
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Register Fail',[$validator->errors()],400);
        }
        $password = Hash::make($request->get('password'));
        //CREATE ACCOUNT CART -> EMPTY CART
        $users = User::create([
            'email' => $request->get('email'),
            'password' => $password,
            'name' => $request->get('name'),
            'avatar' => null,
            'birthday' => null,
            'phone' => empty($request->get('phone')) ? $request->get('phone') : null,
            'role_id' => 1,
            'cart_id' => null
        ]);
        $cart = Cart::create([
            'user_id' => $users->id,
        ]);
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = '@/assets/images/' . $file->getClientOriginalName();
            $file->move('@/assets/images', $file->getClientOriginalName());
            $users->avatar = $path;
        }
        if (!empty($request->get('birthday'))) {
            $users->birthday = date('Y-m-d H:i:s', strtotime($request->get('birthday')));
        }
        $users->cart_id = $cart->id;
        $users->save();
        $data = new UserResource($users);
        return $this->sendResponse($data,'Register Successful',200);
    }

    /**
     * LOGIN API
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Login Fail',[$validator->errors()],400);
        }
        if (Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
            $role = Auth::user()->role_id;
            $role_name = [];
            // Authorization API
            switch ($role) {
                case 1:
                    $role_name[] = 'member';
                    break;
                case 2:
                    $role_name[] = 'admin';
                    break;
                case 3:
                    $role_name[] = 'super_admin';
                    break;
            }
            $token = Auth::user()->createToken('bl_shop',$role_name)->accessToken;
            $data = ['access_token'=>$token];
            return $this->sendResponse($data,'Login Success',200);
        }
        return $this->sendError('Login Fail',['Email or password incorrect!'],401);
    }

    /**
     * LOGOUT API
     */
    public function logout()
    {
        if (Auth::check()){
            $user = Auth::user();
            $user->AauthAcessToken()->delete();
            return $this->sendResponse([],'Logout Success',200);
        }
        return $this->sendError('Logout Fail',['Authenticate Not Found'],404);
    }
}
