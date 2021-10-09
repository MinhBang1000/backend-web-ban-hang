<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(['categories'=>Category::all()],200);
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
        $validator = Validator::make($request->all(),$rules = [
            'category_name'=>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $path = null;
        if ($request->hasFile('category_picture')){
            $file = $request->file('category_picture');
            $path = 'images/'.$file->getClientOriginalName();
            $file->move('images',$file->getClientOriginalName());
        }
        $category = Category::create([
            'category_name'=>$request->get('category_name'),
            'category_picture'=>$path
        ]);
        return response()->json(['category'=>$category],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)){
            return response()->json(['msg'=>'Not Found'],404);
        }
        return response()->json(['category'=>$category],200);
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

    public function updateById(Request $request,$id){
        $category = Category::find($id);
        if (is_null($category)){
            return response()->json(['msg'=>'Not Found'],404);
        }
        $validator = Validator::make($request->all(),$rules = [
            'category_name'=>'required',
        ]);
        if ($validator->fails()){
            return response()->json(['msg'=>$validator->errors()],400);
        }
        $category->category_name = $request->get('category_name');
        $path = null;
        if ($request->hasFile('category_picture')){
            $file = $request->file('category_picture');
            $path = 'images/'.$file->getClientOriginalName();
            $file->move('images',$file->getClientOriginalName());
            $category->category_picture = $path;
        }
        $category->save();
        return response()->json(['category'=>$category],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)){
            return response()->json(['msg'=>'Not Found'],404);
        }
        $category->delete();
        return response()->json(['msg'=>'Delete Done'],200);
    }
}
