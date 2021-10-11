<?php

namespace App\Http\Resources;

use App\Models\Cart;
use App\Models\Role;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $role = Role::find($this->role_id);
        $cart = Cart::find($this->cart_id);
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'email'=>$this->email,
            'avatar'=>$this->avatar,
            'birthday'=>$this->birthday,
            'phone'=>$this->phone,
            'role'=>$role,
            'cart'=>$cart,
            'created_at'=>$this->created_at,
            'updated_at'=>$this->updated_at,
        ];
    }
}
