<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Modules\Product\Entities\Product;
use Hashids\Hashids;
use DataTables;
use Auth;
use File;
use DB;
use Gate;

class ProductimageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Gate::allows('isStaff'))
        {
           return response()->view('error.404', [], 404);
        }
        $url = config('app.url_images');
        $id = "temporary_id";
	      return view('product::productimage', compact('url','id'));
    }

    public function getProduct()
    {
        if (!Gate::allows('isStaff'))
        {
           return response()->view('error.404', [], 404);
        }
        $products = Product::all();
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $url = config('app.url_images');
        $no = 0;
        $data = array();
        foreach ($products as $product) {
          $no ++;
          $row = array();
          $row[] = $no;
          $row[] = $hashids->encode($product->id);
          $row[] = $product->name;
          $row[] = $product->code.' - '.$product->name.' - '.$product->brand.' - '
                  .$product->model.' - '.$product->color;
          $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                    <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span></button>";
          $row[] = $url.'/'.$product->image_path['image1'];
          $row[] = $url.'/'.$product->image_path['image2'];
          $row[] = $url.'/'.$product->image_path['image3'];
          $row[] = $url.'/'.$product->image_path['image4'];
          $row[] = $url.'/'.$product->image_path['image5'];
          $row[] = $url.'/'.$product->image_path['image6'];
          $row[] = $url.'/'.$product->image_path['image7'];
          $row[] = $url.'/'.$product->image_path['image8'];
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function productimageedit(Request $request, $id)
    {
        if($id == "temporary_id"){
          flash()->overlay('Info', 'Select Product First');
          return back();
        }else{
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
        }

        $this->validate($request, [
        'image1' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image2' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image3' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image4' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image5' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image6' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image7' => 'mimes:jpeg,jpg,bmp,png|max:1024',
        'image8' => 'mimes:jpeg,jpg,bmp,png|max:1024',
          ]);

        $old_image=Product::where('id',$ids)->first();

        $old_image_path1=$old_image->image_path['image1'];
        $old_image_path2=$old_image->image_path['image2'];
        $old_image_path3=$old_image->image_path['image3'];
        $old_image_path4=$old_image->image_path['image4'];
        $old_image_path5=$old_image->image_path['image5'];
        $old_image_path6=$old_image->image_path['image6'];
        $old_image_path7=$old_image->image_path['image7'];
        $old_image_path8=$old_image->image_path['image8'];

        if ($request->hasFile('image1')) {
          if($old_image_path1=='products/images/product.png' or $old_image_path1== null){
            $name = str_random(11)."_".$request->file('image1')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image1'),$name);
            $path1 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image1')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image1'),$name);
            Storage::disk('local')->delete($old_image_path1);
            $path1 = 'products/images/'.$name;
          }
        }else{
            $path1= $old_image_path1;
        }
        if ($request->hasFile('image2')) {
          if($old_image_path2=='products/images/product.png' or $old_image_path2== null){
            $name = str_random(11)."_".$request->file('image2')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image2'),$name);
            $path2 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image2')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image2'),$name);
            Storage::disk('local')->delete($old_image_path2);
            $path2 = 'products/images/'.$name;
          }
        }else{
            $path2= $old_image_path2;
        }
        if ($request->hasFile('image3')) {
          if($old_image_path3=='products/images/product.png' or $old_image_path3== null){
            $name = str_random(11)."_".$request->file('image3')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image3'),$name);
            $path3 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image3')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image3'),$name);
            Storage::disk('local')->delete($old_image_path3);
            $path3 = 'products/images/'.$name;
          }
        }else{
            $path3= $old_image_path3;
        }
        if ($request->hasFile('image4')) {
          if($old_image_path4=='products/images/product.png' or $old_image_path4== null){
            $name = str_random(11)."_".$request->file('image4')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image4'),$name);
            $path4 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image4')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image4'),$name);
            Storage::disk('local')->delete($old_image_path4);
            $path4 = 'products/images/'.$name;
          }
        }else{
            $path4= $old_image_path4;
        }
        if ($request->hasFile('image5')) {
          if($old_image_path5=='products/images/product.png' or $old_image_path5== null){
            $name = str_random(11)."_".$request->file('image5')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image5'),$name);
            $path5 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image5')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image5'),$name);
            Storage::disk('local')->delete($old_image_path5);
            $path5 = 'products/images/'.$name;
          }
        }else{
            $path5= $old_image_path5;
        }
        if ($request->hasFile('image6')) {
          if($old_image_path6=='products/images/product.png' or $old_image_path6== null){
            $name = str_random(11)."_".$request->file('image6')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image6'),$name);
            $path6 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image6')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image6'),$name);
            Storage::disk('local')->delete($old_image_path6);
            $path6 = 'products/images/'.$name;
          }
        }else{
            $path6= $old_image_path6;
        }
        if ($request->hasFile('image7')) {
          if($old_image_path7=='products/images/product.png' or $old_image_path7== null){
            $name = str_random(11)."_".$request->file('image7')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image7'),$name);
            $path7 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image7')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image7'),$name);
            Storage::disk('local')->delete($old_image_path7);
            $path7 = 'products/images/'.$name;
          }
        }else{
            $path7= $old_image_path7;
        }
        if ($request->hasFile('image8')) {
          if($old_image_path8=='products/images/product.png' or $old_image_path8== null){
            $name = str_random(11)."_".$request->file('image8')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image8'),$name);
            $path8 = 'products/images/'.$name;
          }else{
            $name = str_random(11)."_".$request->file('image8')->getClientOriginalName();
            Storage::disk('local')->putFileAs('products/images', $request->file('image8'),$name);
            Storage::disk('local')->delete($old_image_path8);
            $path8 = 'products/images/'.$name;
          }
        }else{
            $path8= $old_image_path8;
        }
        $imageproduct = [
          "image1" => $path1,
          "image2" => $path2,
          "image3" => $path3,
          "image4" => $path4,
          "image5" => $path5,
          "image6" => $path6,
          "image7" => $path7,
          "image8" => $path8,
        ];
        $image=['image_path'=>$imageproduct];
        Product::find($ids)->update($image);
        flash()->success('Success', 'Product image saved');
        return back();
    }

}
