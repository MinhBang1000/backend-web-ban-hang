<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['product'=>Product::all()],200);
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
        $validator = Validator::make($request->all(),[
            'product_code'=>'required',
            'product_name'=>'required',
            'product_link'=>'required',
            'product_price'=>'required|numeric',
            'product_percent'=>'required',
            'product_describe'=>'required'
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $product = Product::create([
            'product_code'=>$request->get('product_code'),
            'product_name'=>$request->get('product_name'),
            'product_link'=>$request->get('product_link'),
            'product_price'=>$request->get('product_price'),
            'product_percent'=>$request->get('product_percent'),
            'product_describe'=>$request->get('product_describe'),
            'category_id'=>$request->get('category_id'),
        ]);
        $collection = [];
        if ($request->hasFile('product_picture')){
            $files = $request->file('product_picture');
            foreach ($files as $file){
                $path = 'images/'.$file->getClientOriginalName();
                $file->move('images',$file->getClientOriginalName());
                $collection[] = Collection::create([
                    'product_picture'=>$path,
                    'product_id'=>$product->id,
                ]);
            }
            $temp = $product;
            $category = $temp->category;
            return response(['product'=>$product,'category'=>$category,'collection'=>$collection],200);
        }
        $temp = $product;
        $category = $temp->category;
        return response(['product'=>$product,'category'=>$category,'collection'=>'Haven`t picture'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        $product_1 = Product::find($id);
        if (is_null($product)){
            return response()->json(['msg'=>'Not found by this id'],404);
        }
        $category = $product_1->category;
        $collections = $product_1->collections;
        return response()->json(['product'=>$product,'category'=>$category,'collections'=>$collections],200);   
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
        //...
    }

    public function updateById(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'product_code'=>'required',
            'product_name'=>'required',
            'product_link'=>'required',
            'product_price'=>'required|numeric',
            'product_percent'=>'required',
            'product_describe'=>'required'
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $product = Product::find($id);
        $product->product_code = $request->get('product_code');
        $product->product_name = $request->get('product_name');
        $product->product_link = $request->get('product_link');
        $product->product_percent = $request->get('product_percent');
        $product->product_price = $request->get('product_price');
        $product->product_describe = $request->get('product_describe');
        $product->save();
        $temp = $product;
        if (is_null($product)){
            return response()->json(['msg'=>'Not found by this id'],404);
        }
        if ($request->file('product_picture')){
            $files = $request->file('product_picture');
            Collection::where('product_id','=',$product->id)->delete();
            $collections = [];
            foreach ($files as $file){
                $path = 'images/'.$file->getClientOriginalName();
                $file->move('images',$file->getClientOriginalName());
                $collections[] = Collection::create([
                    'product_picture'=>$path,
                    'product_id'=>$product->id,
                ]);
            }
            
            return response(['product'=>$product,'category'=>$temp->category,'collection'=>$collections],200);
        }
        return response(['product'=>$product,'category'=>$temp->category,'collection'=>null],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);
        if (is_null($product)){
            return response()->json(['msg'=>'Not found by this id'],404);
        }
        $collections = Collection::where('product_id','=',$product->id);
        Collection::where('product_id','=',$id)->delete();
        $product->delete();
        return response()->json(['msg'=>'Delete Done'],200);
    }
}
