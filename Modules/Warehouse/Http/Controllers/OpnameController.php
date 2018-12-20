<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Outlet;
use Modules\Warehouse\Entities\Product;
use Modules\Warehouse\Entities\Inventory;
use Modules\Warehouse\Entities\Opnamedetail;
use Modules\Warehouse\Entities\Stockopname;
use Modules\Warehouse\Entities\User;
use Hashids\Hashids;
use DateTime;
use DataTables;
use Auth;
use DB;
use Gate;

class OpnameController extends Controller
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
          return view('warehouse::stockopname.stockopname',compact('warehouses'));
      }


      public function getOpname()
      {
          if (!Gate::allows('isManager'))
          {
             return response()->view('error.404', [], 404);
          }
          $opnames = Stockopname::all();
          $no = 0;
          $data = array();
          foreach ($opnames as $opname) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $opname->id;
            $row[] = $opname->no_stockopname;
            $row[] = $opname->warehouse->name;
            $row[] = $opname->user->name;
            $row[] = $opname->stockopname_date->format('d-m-Y');
            $row[] = $opname->note;
            if ($opname->status == "Process") {
                $row[] = "<a href='/stockopname/$opname->no_stockopname' class='btn btn-success btn-sm'  role='button' title='Opname'>Opname</i></a>";
                $row[] = $opname->status;
            } elseif ($opname->status == "Finish") {
                $row[] = "Stock Opname Finish";
                $row[] = $opname->status;
            }
            $data[] = $row;
          }

        return DataTables::of($data)->escapeColumns([])->make(true);
      }


      public function opname(Request $request)
      {
            $this->validate($request, [
                    'no_stockopname' => 'required',
                    'stockopname_date' => 'required',
                    'location' => 'required',
           ]);
           $date = DateTime::createFromFormat('d-M-Y', $request->stockopname_date)->format('Y-m-d');

           $stockopname = [
             'no_stockopname' => $request->no_stockopname,
             'stockopname_date' => $date,
             'location' => $request->location,
             'note' => $request->note,
             'status' => "Process",
             'user_id' => Auth::user()->id
           ];
           $warehouse = Warehouse::where('code',$request->location)->pluck('id');
           $inventory = Inventory::where('warehouse', $warehouse)->where('arr_date','<=', $date)->where('opname',null)->get();
           $inventoryq = Inventory::where('warehouse', $warehouse)->where('arr_date','<=', $date)->where('opname',null);
           $inventoryqq = Inventory::where('warehouse', $warehouse)->first();

           if($inventoryqq->opname){
               flash()->success('Info', 'Stock Opname Sedang di Lakukan');
               return back();
           }else{
               $opname['opname'] = $request->no_stockopname;
               $stockopname=Stockopname::create($stockopname);
               foreach($inventory as $item ) {
                  $data[] = [
                      'id_product' => $item->id_product,
                      'user_id' => $item->user_id,
                      'in_out_qty' => $item->in_out_qty,
                      'measure' => $item->measure,
                      'warehouse' => $item->warehouse,
                      'sub_total' => $item->sub_total,
                      'type' => $item->type,
                      'arr_date' => $item->arr_date,
                      'note' => $item->note,
                      'opname_id' => $stockopname->id,
                  ];
               }
               $inventoryq->update($opname);
               DB::table('opnamedetail')->insert($data);

          }
            flash()->success('Success', 'Stock Opname is Ready');
            return back();
      }

      public function detailopname($id)
      {
            $inventory = Inventory::where('opname',$id)->first();
            return view('warehouse::stockopname.stockopnamedetail',compact('inventory'));
      }

      public function getOpnamedetail($id)
      {
        $stockopname=Stockopname::where('no_stockopname',$id)->first();
        $opname = Opnamedetail::selectRaw('ANY_VALUE(id),id_product,SUM(in_out_qty) as quantity,measure,warehouse')
        ->where('opname_id',$stockopname->id)->groupBy('id_product','measure','warehouse')->get();

        $no = 0;
        $data = array();
        foreach ($opname as $index => $opname) {
          $no ++;
          $row = array();
          $row[] = $no;
          $row[] = $opname->id;
          $row[] = $opname->product->code;
          $row[] = $opname->product->name;
          $row[] = $opname->measure;
          $row[] = "<input type='text' style='text-align:right' id='opname' name='opname' value='$opname->quantity'>";
          $row[] = $opname->maks->quantity;
          $row[] = "<a href='#' onclick='showForm(\"".$opname->hashproduct."\",\"".$opname->product->name."\")' title='Persediaan'><i class='fa fa-tag'></i></a>
                                      &nbsp;&nbsp;&nbsp;
                        <a href='#' id='clicksave' title='Save Data'><i class='fa fa-save'></i></a>";
          $row[] = $stockopname->id;
          $row['picture'] = Storage::disk('local')->url($opname->product->image_path['image1']);
          $row['product'] = $opname->hashproduct;
          $row['price'] = number_format($opname->maks->price,4);
          $row['stock'] = $opname->quantity;
          $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function opnamestore(Request $request)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);

          $data = $request->stock-$request->recorded;
          $diff = $request->opname-$request->recorded;
          $difference = $diff-$data;
          $subtotal =str_replace(",", "", $request->subtotal);
          $data = [
            'id_product' => $hashids->decode($request->id_product)[0],
            'user_id' => Auth::user()->id,
            'in_out_qty' => $difference,
            'measure' => $request->measure,
            'type' => '4',
            'arr_date' => date('Y-m-d'),
            'note' => 'Stock Opname Adjustment',
            'warehouse' => $hashids->decode($request->warehouse)[0],
            'sub_total' => $subtotal,
            'opname_id' => $request->noopname,
          ];

          Opnamedetail::create($data);

          flash()->success('Success', 'Data is Saved');
          return back();
      }

      public function getDataopname($id, $idwh)
      {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];
        $idwhs=$hashids->decode($idwh)[0];

        $opnamedetails = Opnamedetail::where('id_product', $ids)->where('warehouse',$idwhs)->get();

        $no = 0;
        $data = array();
        foreach ($opnamedetails as $detail) {
          $no ++;
          $row = array();
          $row[] = $no;
          $row[] = $detail->id;
          $row[] = $detail->product->code;
          $row[] = $detail->product->name;
          $row[] = $detail->in_out_qty;
          $row[] = $detail->measure;
          $row[] = $detail->note;
          $row[] = $detail->user->name;
          $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function opnamefinish($id)
      {
         $stockopname = Stockopname::where('no_stockopname', $id)->first();
         $opname = Opnamedetail::selectRaw('ANY_VALUE(id),id_product,SUM(in_out_qty) as quantity, SUM(sub_total) as sub_total, measure,warehouse')
         ->where('opname_id',$stockopname->id)->groupBy('id_product','measure','warehouse')->get();
         $data = array();
         foreach($opname as $item ) {
            $data[] = [
                'id_product' => $item->id_product,
                'user_id' => Auth::user()->id,
                'in_out_qty' => $item->quantity,
                'measure' => $item->measure,
                'warehouse' => $item->warehouse,
                'sub_total' => $item->sub_total,
                'arr_date' => date('Y-m-d'),
                'type' => '4',
                'note' => "Stockopname No.".$id,
            ];
         }
         $status['status']="Finish";
         DB::table('inventory')->insert($data);
         Inventory::where('opname', '=', $id)->delete();
         Stockopname::where('no_stockopname', '=', $id)->update($status);

         return redirect('/stockopname');
      }
}
