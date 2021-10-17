<?php

namespace App\Http\Resources;

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
            $product = $d->products;
            $product->product_amount = $d->product_amount;
            $product[] = $product;
        }
        return [
            'user_id'=>$this->user_id,
            'product'=>$products,
        ];
    }
}
