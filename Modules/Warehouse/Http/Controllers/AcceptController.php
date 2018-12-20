<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Purchasedetail;
use Modules\Warehouse\Entities\Purchase;
use Modules\Warehouse\Entities\Send;
use Modules\Warehouse\Entities\Product;
use Modules\Warehouse\Entities\Inventory;
use Modules\Warehouse\Entities\User;
use Hashids\Hashids;
use DateTime;
use DataTables;
use Auth;
use Carbon\Carbon;
use Gate;

class AcceptController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index($id)
      {
          if (!Gate::allows('isStaff'))
          {
             return response()->view('error.404', [], 404);
          }
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          $warehouse = Warehouse::find($ids);
          return view('warehouse::accept',compact('warehouse'));
      }

      public function getDelivery($id)
      {
        if (!Gate::allows('isStaff'))
        {
           return response()->view('error.404', [], 404);
        }
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $purchases = Purchase::where(['sendto'=>$ids, 'send_date'=>null])->get();
        $lokasi = Warehouse::where('id', $ids )->first();
        $sends = Send::where(['sendto'=>$lokasi->code, 'arr_date'=>null])->get();

        $no = 0;
        $data = array();
        foreach ($purchases as $purchase) {
          $no ++;
          $row = array();
          $rowdetail = array();
          $row[] = $no;
          $row[] = $purchase->hashid;
          $row[] = $purchase->invoice_id;
          if(count($purchase->supplier)==0){
            $row[] = "No Supplier/ Deleted";
          }else{
            $row[] = $purchase->supplier->name;
          }
          $row[] = $purchase->order_date->format('d-M-Y');
          $row[] = "<button type='button' id='clickterima' class='btn btn-success'>Accept</button>";
          $row[] = "1";
          foreach ($purchase->purchasedetail as $detail) {
            $rowdetail['idproduct'][] = $detail->hashproductid;
            $rowdetail['product'][] = $detail->product->name;
            $rowdetail['quantity'][] = $detail->quantity;
            $rowdetail['measure'][] = $detail->measure;
            $rowdetail['sub_total'][] = $detail->sub_total;
          }
          $row['idproduct'] = $rowdetail['idproduct'];
          $row['product'] = $rowdetail['product'];
          $row['quantity'] = $rowdetail['quantity'];
          $row['measure'] = $rowdetail['measure'];
          $row['sub_total'] = $rowdetail['sub_total'];
          $data[] = $row;
        }

        foreach ($sends as $send) {
          $no ++;
          $row = array();
          $rowdetail = array();
          $row[] = $no;
          $row[] = $send->hashid;
          $row[] = $send->no_letter;
          if(count($send->from)==0){
            $row[] = "No Warehouse/ Deleted";
          }else{
            $row[] = $send->warehousefrom->name;
          }
          $row[] = $send->letter_date->format('d-M-Y');
          $row[] = "<button type='button' id='clickterima' class='btn btn-success'>Accept</button>";
          $row[] = "2";
          foreach ($send->senddetail as $detail) {
              $rowdetail['idproduct'][] = $detail->hashproduct;
              $rowdetail['product'][] = $detail->product->name;
              $rowdetail['quantity'][] = $detail->quantity;
              $rowdetail['measure'][] = $detail->measure;
              $rowdetail['sub_total'][] = $detail->sub_total;
          }
          $row['idproduct'] = $rowdetail['idproduct'];
          $row['product'] = $rowdetail['product'];
          $row['quantity'] = $rowdetail['quantity'];
          $row['measure'] = $rowdetail['measure'];
          $row['sub_total'] = $rowdetail['sub_total'];
          $data[] = $row;
        }
        return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function Accept(Request $request, $id)
      {
             $send_date = DateTime::createFromFormat('d-M-Y', $request->send_date)->format('Y-m-d');
             $purchase['send_date'] = $send_date;
             $send['arr_date'] = $send_date;

             $hash = config('app.hash_key');
             $hashids = new Hashids($hash,20);
             $ids=$hashids->decode($id)[0];

             $product_id = $request->product_id;
             $quantity = $request->quantity;
             $measure = $request->measure;
             $warehouse=$hashids->decode($request->warehouse)[0];
             $lokasi = $request->lokasi;
             $sub_total = $request->sub_total;
             if ($request->r==2) {
               $note = "Accepted From ".$request->from." ".$request->invoice_id;
               $type = "6";
             }else{
               $note = "Purchase From ".$request->from." ".$request->invoice_id;
               $type = "7";
             }
             foreach ($product_id as $key => $m )  {
                 $inventory= [
                     'id_product' => $hashids->decode($m)[0],
                     'in_out_qty' => $quantity[$key],
                     'measure' => $measure[$key],
                     'type' => $type,
                     'warehouse' => $warehouse,
                     'sub_total' => $sub_total[$key],
                     'arr_date' => $send_date,
                     'user_id' => Auth::user()->id,
                     'note' => $note,
                 ];
                 Inventory::create($inventory);
            }
           if ($request->r==2) {
             Send::find($ids)->update($send);
           }else{
             Purchase::find($ids)->update($purchase);
           }
            flash()->success('Success', 'Items Accepted');
            return back();
      }
}
