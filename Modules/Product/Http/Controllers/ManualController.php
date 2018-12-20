<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\Inventory;
use Modules\Product\Entities\Warehouse;
use Modules\Product\Entities\User;
use Modules\Product\Entities\Type;
use Hashids\Hashids;
use DateTime;
use DataTables;
use Auth;
use Carbon\Carbon;
use Gate;

class ManualController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          if (!Gate::allows('isManager'))
          {
             return response()->view('error.404', [], 404);
          }
          $warehouses = Warehouse::all();
          $types = Type::all();
          return view('product::manual',compact('warehouses','types'));
      }

      public function getProduct()
      {
        if (!Gate::allows('isManager'))
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
          $row[] = $product->code;
          $row[] = $product->name;
          $row[] = $product->brand;
          $row[] = $product->model;
          $row[] = $product->color;
          $row[] = $product->hazardwarning;
          $row[] = $product->warranty_type;
          $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                        <span class='glyphicon glyphicon-plus' aria-hidden='true'> </span></button>";
          $row[] = $product->user->name;
          $row[] = $url.'/'.$product->image_path['image1'];
          $row[] = $product->measure;
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
       }



      public function getInventoryManual()
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $inventories = Inventory::whereIn('type', ['1','2','3','4'])->get();
          $no = 0;
          $data = array();
          foreach ($inventories as $inventory) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($inventory->id);
            $row[] = $inventory->product->code;
            $row[] = $inventory->product->name;
            $row[] = $inventory->in_out_qty;
            $row[] = $inventory->measure;
            $row[] = $inventory->warehousedata->name;
            $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($inventory->id)."\")'><i class='fa fa-pencil-square-o'></i></a>
                          &nbsp;&nbsp;&nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($inventory->id)."\")' type='submit'><i class='fa fa-trash'></i></a>";
            $row[] = number_format($inventory->sub_total,2);
            $row[] = $inventory->typedata->id.' - '.$inventory->typedata->name;
            $row[] = $inventory->note;
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function inputManual(Request $request)
      {
            $hash = config('app.hash_key');
            $hashids = new Hashids($hash,20);
            $ids=$hashids->decode($request->id_product)[0];

            $this->validate($request, [
                      'in_out_qty' => 'required',
                      'price' => 'required',
                      'warehouse' => 'required',
                      'type' => 'required',
            ]);

            $sub_total = str_replace(",", "", $request->sub_total);
            $current_time = Carbon::now()->toDateTimeString();
            $inventory = [
              'id_product' => $ids,
              'measure' => $request->measure,
              'sub_total' => $sub_total,
              'in_out_qty' => $request->in_out_qty,
              'warehouse' => $request->warehouse,
              'type' => $request->type,
              'arr_date' => $current_time,
              'note' => "Input Manual ".$request->note,
              'user_id' => Auth::user()->id,
            ];

            Inventory::create($inventory);
            flash()->success('Success', 'Manual Input Recorded');
            return back();
      }

      public function manualedit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $inventory=Inventory::find($ids);

          $data = [
            'id' => $hashids->encode($inventory->id),
            'id_product' => $hashids->encode($inventory->id_product),
            'user_id' => $hashids->encode($inventory->user_id),
            'code' => $inventory->product->code,
            'name'=> $inventory->product->name,
            'in_out_qty' => $inventory->in_out_qty,
            'measure' => $inventory->measure,
            'note' => $inventory->note,
            'warehouse' => $inventory->warehouse,
            'sub_total' => $inventory->sub_total,
            'type' => $inventory->type,
            'arr_date' => $inventory->arr_date,
            'opname' => $inventory->opname,
          ];

          echo json_encode($data);
      }

      public function manualupdate(Request $request, $id)
      {
            $hash = config('app.hash_key');
            $hashids = new Hashids($hash,20);
            $ids=$hashids->decode($id)[0];
            $id_product=$hashids->decode($request->id_product)[0];

            $this->validate($request, [
                      'in_out_qty' => 'required',
                      'price' => 'required',
                      'warehouse' => 'required',
                      'type' => 'required',
            ]);

            $sub_total = str_replace(",", "", $request->sub_total);
            $data=[
              'id_product' => $id_product,
              'measure' => $request->measure,
              'sub_total' => $sub_total,
              'in_out_qty' => $request->in_out_qty,
              'warehouse' => $request->warehouse,
              'type' => $request->type,
              'note' => $request->note,
              'user_id' => Auth::user()->id,
            ];

           Inventory::find($ids)->update($data);
           flash()->success('Success', 'Manual Input is Updated');
           return back();
      }

      public function manualdelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          Inventory::destroy($ids);
          flash()->success('Success', 'Manual Input Data Deleted');
          return back();
      }
}
