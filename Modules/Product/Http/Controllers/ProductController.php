<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\Measure;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\User;
use Hashids\Hashids;
use DataTables;
use Auth;
use File;
use DB;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $url = config('app.url_images');
        $measure = Measure::all();
        $brand = Brand::all();

	      return view('product::product', compact('measure', 'brand','url'));
    }

    public function getProduct()
    {
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
          $row[] = $product->code;
          $row[] = $product->name;
          $row[] = $product->brand;
          $row[] = $product->model;
          $row[] = $product->color;
          $row[] = $product->hazardwarning;
          $row[] = $product->warranty_type;
          $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($product->id)."\")'><i class='fa fa-pencil-square-o'></i></a>
                        &nbsp;&nbsp;&nbsp;
                      <a href='#' onclick='deleteForm(\"".$hashids->encode($product->id)."\")' type='submit'><i class='fa fa-trash'></i></a>";
          $row[] = $product->user->name;
          $row[] = $url.'/'.$product->image_path['image1'];
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function productstore(Request $request)
    {
        $this->validate($request, [
          'name' => 'required|unique:product',
          'code' => 'required|unique:product',
          'measure' => 'required',
          'brand' => 'required',
          'model' => 'required',
          'color' => 'required',
          'warranty_type' => 'required',
          'hazardwarning' => 'required',
        ]);
        $images =[
            'image1' => 'products/images/product.png',
            'image2' => 'products/images/product.png',
            'image3' => 'products/images/product.png',
            'image4' => 'products/images/product.png',
            'image5' => 'products/images/product.png',
            'image6' => 'products/images/product.png',
            'image7' => 'products/images/product.png',
            'image8' => 'products/images/product.png',
          ];
        $data=[
          'name' => $request->name,
          'code' => $request->code,
          'measure' => $request->measure,
          'brand' => $request->brand,
          'model' => $request->model,
          'color' => $request->color,
          'warranty_type' => $request->warranty_type,
          'hazardwarning' => $request->hazardwarning,
          'image_path' => $images,
          'user_id' => Auth::user()->id
        ];

        Product::create($data);
	      flash()->success('Success', 'Product is Added');
        return redirect('product');
    }

    public function productgen($string)
    {
        $results = ''; // empty string
        $l=2;
        $vowels = array('a', 'e', 'i', 'o', 'u', 'y'); // vowels
        preg_match_all('/[A-Z][a-z]*/', ucfirst($string), $m); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($m[0] as $substring){
            $substring = str_replace($vowels, '', strtolower($substring)); // String to lower case and remove all vowels
            $results .= preg_replace('/([a-z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        $id=Product::where('code', 'like', $results . '%')->count()+1;
        $results .= '-'. str_pad($id, 4, 0, STR_PAD_LEFT); // Add the ID
        echo json_encode($results);
    }

    public function productedit($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $product=Product::where('id',$ids)->first();
        $barang = [ 'code'=>$product->code,
                    'name'=>$product->name,
                    'measure'=>$product->measure,
                    'brand'=>$product->brand,
                    'model'=>$product->model,
                    'color'=>$product->color,
                    'hazardwarning'=>$product->hazardwarning,
                    'warranty_type'=>$product->warranty_type,
                 ];
        return json_encode($barang);
    }

    public function productupdate(Request $request, $id)
    {
         $hash = config('app.hash_key');
         $hashids = new Hashids($hash,20);
         $ids=$hashids->decode($id)[0];
         $this->validate($request, [
             'name' => [
                 'required',
                 Rule::unique('product')->ignore($ids),
             ],
             'code' => [
                 'required',
                 Rule::unique('product')->ignore($ids),
             ],
             'measure' => 'required',
             'brand' => 'required',
             'model' => 'required',
             'color' => 'required',
             'warranty_type' => 'required',
             'hazardwarning' => 'required',
         ]);
         $data=[
           'name' => $request->name,
           'code' => $request->code,
           'measure' => $request->measure,
           'brand' => $request->brand,
           'model' => $request->model,
           'color' => $request->color,
           'warranty_type' => $request->warranty_type,
           'hazardwarning' => $request->hazardwarning,
           'user_id' => Auth::user()->id
         ];

         Product::find($ids)->update($data);
         flash()->success('Success', 'Product is Updated');
         return redirect('product');
    }

    public function productdelete($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        Product::destroy($ids);
        flash()->success('Success', 'Product Deleted');
        return redirect('product');
    }

}
