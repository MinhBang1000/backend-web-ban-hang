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

    public function addProduct(Request $request, $id){
        $cart_id = Auth::user()->cart_id;
        $product_id = $id;
        $product_amount = $request->get('product_amount');
        $detailcarts = DetailCart::where('cart_id','=',$cart_id)->where('product_id','=',$product_id)->get();
        if (count($detailcarts)==0){
            $detail = DetailCart::create([
                'cart_id'=>$cart_id,
                'product_id'=>$product_id,
                'product_amount'=>$product_amount,
            ]);
        }else{
            $detailcarts[0]->product_amount += $product_amount;
            $detailcarts[0]->save();
        }
        
        return $this->sendResponse([],'Add Product To Cart Success',200);
    }

    public function updateProduct(Request $request, $id){
        $cart_id = Auth::user()->cart_id;
        $product_id = $id;
        $product_amount = $request->get('product_amount');
        $detailcarts = DetailCart::where('cart_id','=',$cart_id)->where('product_id','=',$product_id)->get();
        if (count($detailcarts)==0){
            return $this->sendError(['Not Found'],404);
        }else{
            $detailcarts[0]->product_amount = $product_amount;
            $detailcarts[0]->save();
        }
        return $this->sendResponse([],'Change Amount A Product In Your Cart Success',200);
    }

    public function removeProduct(Request $request, $id){
        $cart_id = Auth::user()->cart_id;
        $product_id = $id;
        $detailcarts = DetailCart::where('cart_id','=',$cart_id)->where('product_id','=',$product_id)->get();
        if (count($detailcarts)==0){
            return $this->sendError(['Not Found'],404);
        }else{
            $detailcarts[0]->delete();
        }
        return $this->sendResponse([],'Remove Product In Your Cart Success',200);
    }
}
