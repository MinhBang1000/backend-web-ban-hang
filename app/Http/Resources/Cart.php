<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class Cart extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $details = $this->detailcarts;
        $products = [];
        foreach ($details as $d){
            $product = Product::find($d->product_id);
            $product->product_amount = $d->product_amount;
            $products[] = $product;
        }
        return [
            'user_id'=>$this->user_id,
            'product'=>$products,
        ];
    }
}
