<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart as ResourcesCart;
use App\Models\Cart;
use App\Models\DetailCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function show(){
        $id = Auth::user()->cart_id;
        $cart = Cart::find($id);
        return $this->sendResponse(new ResourcesCart($cart),'Show Cart Success',200);
    }

    public function addProduct(Request $request){
        $id = Auth::user()->cart_id;
        $product_id = $request->get('product_id');
        $product_amount = $request->get('product_amount');
        $detailcarts = DetailCart::where('cart_id','=',$id)->get();
        $total = 0;
        foreach ($detailcarts as $d){
            if ($d->product_id===$product_id){
                $total = $d->product_amount;
            }
        }
        $detail = DetailCart::create([
            'cart_id'=>$id,
            'product_id'=>$product_id,
            'product_amount'=>$product_amount,
        ]);
        if ($total!=0){
            $detail->product_amount += $total;
        }
        return $this->sendResponse([],'Add Product To Cart Success',200);
    }

    public function removeProduct(Request $request){
        $id = Auth::user()->cart_id;
        $product_id = $request->get('product_id');
        $detailcarts = DetailCart::where('cart_id','=',$id)->get();
        $check = 0;
        foreach ($detailcarts as $d){
            if ($d->product_id===$product_id){
                $d->delete();
                $check = 1;
            }
        }
        if ($check == 0){
            return $this->sendError(['Not Found'],404);
        }
        return $this->sendResponse([],'Remove Product In Your Cart Success',200);
    }
}
