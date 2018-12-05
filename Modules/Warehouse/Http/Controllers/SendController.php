<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Send;
use Modules\Warehouse\Entities\Senddetail;
use Modules\Warehouse\Entities\Inventory;
use Modules\Warehouse\Entities\Inventorysum;
use Modules\Warehouse\Entities\Product;
use Modules\Warehouse\Entities\Supplier;
use Modules\Warehouse\Entities\Measure;
use Modules\Warehouse\Entities\User;
use Hashids\Hashids;
use DateTime;
use DataTables;
use Auth;
use PDF;
use File;
use DB;
use Carbon\Carbon;

class SendController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $warehouse = Warehouse::where('id', $ids)->first();
	      return view('warehouse::send.send',compact('warehouse'));
    }

    public function getSend($id)
    {
        $sends = Send::where('from',$id)->get();
        $no = 0;
        $data = array();
        foreach ($sends as $send) {
          $no ++;
          $row = array();
          $rowdetail = array();
          $row[] = $no;
          $row[] = $send->hashid;
          $row[] = $send->no_letter;
          if(count($send->warehousefrom)==0){
            $row[] = "No Warehouse/ Deleted";
          }else{
            $row[] = $send->warehousefrom->warehouse_name;
          }
          $row[] = $send->letter_date->format('d-M-Y');
          $row[] = $send->note;
          $row[] = $send->user->name;
          if($send->arr_date){
            $row[] = $send->arr_date->format('d-M-Y');
          }else{
            $row[] = "Not Arrive Yet";
          }
          if(count($send->warehousesendto)==0){
            $row[] = "No Warehouse/ Deleted";
          }else{
            $row[] = $send->warehousesendto->name;
          }
          $row[] = $send->imageinvoice_path;
          if($send->arr_date){
            $row[] = "&nbsp;&nbsp;&nbsp;<a href='#' onclick='deleteForm(\"".$send->hashid."\")' type='submit'><i class='fa fa-trash'></i></a>
                      <br>Process Finished";
          }else{
            $row[] = "&nbsp;&nbsp;&nbsp; <a href='/warehouse/".$send->hashid."/send_edit'><i class='fa fa-pencil-square-o'></i></a>
                    &nbsp;&nbsp;&nbsp;<a href='#' onclick='deleteForm(\"".$send->hashid."\")' type='submit'><i class='fa fa-trash'></i></a>";
          }
          $row['note'] = $send->note;
          foreach ($send->senddetail as $detail) {
            $rowdetail['product_id'][] = $detail->product->name;
            $rowdetail['product_code'][] = $detail->product->code;
            $rowdetail['quantity'][] = $detail->quantity;
            $rowdetail['measure'][] = $detail->measure;
          }
          $row['product_id'] = $rowdetail['product_id'];
          $row['product_code'] = $rowdetail['product_code'];
          $row['quantity'] = $rowdetail['quantity'];
          $row['measure'] = $rowdetail['measure'];
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function AddSend($id)
    {
        $warehouses = Warehouse::where('code', '!=', $id)->get();
        $location = Warehouse::where('code', $id )->first();
        return view('warehouse::send.send_items',compact('warehouses','location'));
    }

    public function getInventory($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];
        $url = config('app.url_images');
        $inventories = Inventory::selectRaw('ANY_VALUE(id),id_product,SUM(in_out_qty) as quantity,SUM(sub_total) as sub_total, SUM(sub_total)/SUM(in_out_qty) as hargabeli, measure,ANY_VALUE(warehouse)')
        ->where('warehouse',$ids)
        ->groupBy('id_product','measure')->get();
        $no = 0;
        $data = array();
        foreach ($inventories as $inventory) {
          $no ++;
          $row = array();
          $row[] = $no;
          $row[] = $inventory->hashproduct;
          $row[] = $inventory->product->code;
          $row[] = $inventory->product->name;
          $row[] = $inventory->hargabeli;
          $row[] = $inventory->quantity;
          $row[] = $inventory->measure;
          $row[] = $id;
          $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                    <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span></button>";
          $row[] = $url.'/'.$inventory->product->image_path['image1'];
          $row[] = $inventory->product->brand;
          $row[] = $inventory->product->model;
          $row[] = $inventory->product->color;
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function Sendgen($id)
    {
          $year = date("Y");
          $results='';
          $resultsfrom='';
          $l=3;
          $artificial_name = "DELIVERY";
          $warehouse=Warehouse::where('code',$id)->first();
          $name=$warehouse->name;
          $vowels = array('A', 'E', 'I', 'O', 'U', 'Y'); // vowels
          preg_match_all('/[A-Z][a-z]*/', ucfirst($artificial_name), $m); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
          foreach($m[0] as $substring){
              $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
              $results .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
          }
          preg_match_all('/[A-Z][a-z]*/', ucfirst($name), $k); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
          foreach($k[0] as $substring){
              $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
              $resultsfrom .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
          }
          $id=Send::where('from',$id)->whereYear('created_at', '=', $year)->count()+1;
          $data = str_pad($id, 4, 0, STR_PAD_LEFT)."/".$resultsfrom."/".$results."/".$year; // Add the ID
          echo json_encode($data);
    }


    public function Sendstore(Request $request)
    {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $fromids=$hashids->decode($request->fromid)[0];

          $this->validate($request, [
                  'no_letter' => 'required|unique:send',
                  'sendto' => 'required',
                  'quantity' => 'required',
                  'letter_date' => 'required',
      	  ]);

          $date = DateTime::createFromFormat('d-M-Y', $request->letter_date)->format('Y-m-d');

          $send = [
            'no_letter' => $request->no_letter,
            'letter_date' => $date,
            'from' => $request->from,
            'sendto' => $request->sendto,
            'note' => $request->note,
            'user_id' => Auth::user()->id,
          ];
          $send_id = send::create($send);

          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);

          $product_id = $request->product_id;
          $quantity = $request->quantity;
          $subtotal =str_replace(",", "", $request->sub_total);
          $measure = $request->measure;
          foreach ($product_id as $key => $m )  {
            $inventory= [
                'id_product' => $hashids->decode($m)[0],
                'in_out_qty' => -$quantity[$key],
                'measure' => $measure[$key],
                'warehouse' => $fromids,
                'sub_total' => -$subtotal[$key],
                'arr_date' => $date,
                'type' => '5',
                'user_id' => Auth::user()->id,
                'note' => "Delivery with letter no. ".$request->no_letter." at ".$date,
            ];
            $inventory_id=Inventory::create($inventory);
            $senddetail= [
                'product_id' => $hashids->decode($m)[0],
                'send_id' => $send_id->id,
                'quantity' => $quantity[$key],
                'sub_total' => $subtotal[$key],
                'measure' => $measure[$key],
                'from' => $fromids,
                'user_id' => Auth::user()->id,
                'inventory_id' => $inventory_id->id,
            ];

            Senddetail::create($senddetail);
        }
	      flash()->success('Success', 'Delivery is Made');
        return redirect('/warehouse/addsend/'.$request->from);
    }

    // public function sendshow($id)
    // {
    //     $company = Auth::user()->company_id;
    //     $user_id = User::where('company_id', $company)->pluck('id');
    //     $send=send::find($id);
    //     $pdf=PDF::loadView('send.sendshow',compact('send'));
    //     return $pdf->download($send->invoice_id."-invoice.pdf");
    //
    // }
    //
    public function Sendedit($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $senddetail=Senddetail::where('send_id',$ids)->get();
        $send=Send::find($ids);
        $warehouse = Warehouse::where('code', '!=', $send->from)->get();
        $location = Warehouse::where('code', $send->from )->first();
        $maksimum = Inventorysum::where('warehouse', $location->id )->get();
        return view('warehouse::send.send_items_edit',compact('warehouse','senddetail','send','location','maksimum'));
    }

    public function Sendupdate(Request $request, $id)
    {
         $hash = config('app.hash_key');
         $hashids = new Hashids($hash,20);
         $ids=$hashids->decode($id)[0];
         $fromids=$hashids->decode($request->fromid)[0];

         $this->validate($request, [
                 'no_letter' => [
                   'required',
                    Rule::unique('send')->ignore($ids),
                 ],
                 'sendto' => 'required',
                 'letter_date' => 'required',
     	  ]);

        $date = DateTime::createFromFormat('d-M-Y', $request->letter_date)->format('Y-m-d');

         $send = [
           'no_letter' => $request->no_letter,
           'letter_date' => $date,
           'from' => $request->from,
           'sendto' => $request->sendto,
           'note' => $request->note,
           'user_id' => Auth::user()->id,
         ];

          Send::find($ids)->update($send);

          $senddetails=Senddetail::where('send_id',$ids)->get();
          foreach($senddetails as $senddetail){
                      Inventory::where('id', $senddetail->inventory_id)->forceDelete();
                    }

          Senddetail::where('send_id',$ids)->forceDelete();

          $product_id = $request->product_id;
          $quantity = $request->quantity;
          $subtotal =str_replace(",", "", $request->sub_total);
          $measure = $request->measure;
          foreach ($product_id as $key => $m )  {
            $inventory= [
                'id_product' => $hashids->decode($m)[0],
                'in_out_qty' => -$quantity[$key],
                'measure' => $measure[$key],
                'warehouse' => $fromids,
                'sub_total' => -$subtotal[$key],
                'arr_date' => $date,
                'type' => '5',
                'user_id' => Auth::user()->id,
                'note' => "Delivery with letter no. ".$request->no_letter." at ".$date,
            ];
            $inventory_id=Inventory::create($inventory);
            $senddetail= [
                'product_id' => $hashids->decode($m)[0],
                'send_id' => $ids,
                'quantity' => $quantity[$key],
                'sub_total' => $subtotal[$key],
                'measure' => $measure[$key],
                'from' => $fromids,
                'user_id' => Auth::user()->id,
                'inventory_id' => $inventory_id->id,
            ];
            Senddetail::create($senddetail);
          }
          flash()->success('Success', 'Delivery is Updated');
          return redirect('/warehouse/'.$request->fromid.'/send');
    }

    public function Senddelete($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $senddetails=Senddetail::where('send_id', $ids)->get();
        $send=Send::find($ids);
        foreach ($senddetails as $senddetail )  {
          Inventory::where('id', $senddetail->inventory_id)->delete();
        }
        Senddetail::where('send_id', $ids)->delete();
        Send::where('id', $ids)->delete();

        flash()->success('Success', 'Delivery is Deleted');
        return back();
    }


}
