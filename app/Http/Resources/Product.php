<?php

namespace App\Http\Resources;

use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $category = Category::find($this->category_id);
        $collections = Collection::where('product_id','=',$this->id)->get();
        return [
            'id'=>$this->id,
            'product_code'=>$this->product_code,
            'product_name'=>$this->product_name,
            'product_link'=>$this->product_link,
            'product_price'=>$this->product_price,
            'product_percent'=>$this->product_percent,
            'product_describe'=>$this->product_describe,
            'product_amount'=>$this->product_amount,
            'category'=>$category,
            'collections'=>$collections,
        ];
    }
}
