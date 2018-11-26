<?php

namespace Modules\Warehouse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Modules\Warehouse\Entities\Warehouse;
use Modules\Warehouse\Entities\Send;
use Modules\Warehouse\Entities\Senddetail;
use Modules\Warehouse\Entities\Inventory;
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
          $row[] = $send->id;
          $row[] = $send->no_letter;
        //  dd($send->from);
          if(substr($send->from, 0, 2)=="WH"){
            if(count($send->warehousefrom)==0){
              $row[] = "Gudang Tidak Ada/ Terhapus";
            }else{
              $row[] = $send->warehousefrom->warehouse_name;
            }
          }else{
            if(count($send->outletfrom)==0){
              $row[] = "Outlet Tidak Ada/ Terhapus";
            }else{
              $row[] = $send->ouletfrom->outlet_name;
            }
          }
          $row[] = $send->letter_date->format('d-m-Y');
          $row[] = $send->note;
          $row[] = $send->user->name;
          if($send->arr_date){
            $row[] = $send->arr_date->format('d-m-Y');
          }else{
            $row[] = "Belum Tersend";
          }
          if(substr($send->sendto, 0, 2)=="WH"){
            if(count($send->warehousesendto)==0){
              $row[] = "Gudang Tidak Ada/ Terhapus";
            }else{
              $row[] = $send->warehousesendto->warehouse_name;
            }
          }else{
              if(count($send->outletsendto)==0){
                $row[] = "Outlet Tidak Ada/ Terhapus";
              }else{
                $row[] = $send->outletsendto->outlet_name;
              }
          }
          $row[] = $send->imageinvoice_path;
          if($send->arr_date){
            $row[] = "Pengiriman Selesai";
          }else{
            $row[] = "&nbsp;&nbsp;&nbsp; <a href='/send/".$send->id."/edit'><i class='fa fa-pencil-square-o'></i></i></a>
                    &nbsp;&nbsp;&nbsp;<a href='#' onclick='deleteForm(".$send->id.")' type='submit'><i class='fa fa-trash'></a>";
          }

          foreach ($send->senddetail as $detail) {
            if($detail->jenis==1){
              $rowdetail['skuproduct_id'][] = $detail->product->product_name;
              $rowdetail['skuproduct_code'][] = $detail->product->product_id;
            }else{
              $rowdetail['skuproduct_id'][] = $detail->sku->sku_name;
              $rowdetail['skuproduct_code'][] = $detail->sku->sku_code;
            }
            $rowdetail['quantity'][] = $detail->quantity;
            $rowdetail['measure'][] = $detail->measure;

          }
          $row['skuproduct_id'] = $rowdetail['skuproduct_id'];
          $row['skuproduct_code'] = $rowdetail['skuproduct_code'];
          $row['quantity'] = $rowdetail['quantity'];
          $row['measure'] = $rowdetail['measure'];
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function getInventory($id)
    {
        $company = Auth::user()->company_id;
        $inventory = Inventory::selectRaw('ANY_VALUE(id),id_product,SUM(in_out_qty) as quantity,SUM(sub_total) as sub_total, SUM(sub_total)/SUM(in_out_qty) as hargabeli, measure,ANY_VALUE(gudang)')->where('company_id', $company)->where('gudang',$id)
        ->groupBy('id_product','measure')->get();
        $no = 0;
        $data = array();
        foreach ($inventory as $sedia) {
          $no ++;
          $row = array();
          $row[] = $no;
          $row[] = $sedia->id_product;
          $row[] = $sedia->product->product_id;
          $row[] = $sedia->product->product_name;
          $row[] = $sedia->hargabeli;
          $row[] = $sedia->quantity;
          $row[] = $sedia->measure;
          $row[] = $id;
          $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                    <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span></button>";
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

    public function sendskustore(Request $request)
    {
      //dd($request);
        $pesan = [
                  'required' => 'Ada kolom yang belum diisi.',
                  'unique' => 'Nomor surat, sudah dipakai.'
                ];
        $this->validate($request, [
                'no_letter' => [
                'required',
                Rule::unique('lerp_send')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })
                ],
                'sendto' => 'required',
                'quantity' => 'required',
                'letter_date' => 'required',
    	  ],$pesan);
        $date = DateTime::createFromFormat('d-m-Y', $request->letter_date);
        $tanggal = $date->format('Y-m-d');
        $company = Auth::user()->company_id;
        $user_id = Auth::user()->id;
        $send = [
          'no_letter' => $request->no_letter,
          'letter_date' => $tanggal,
          'from' => $request->from,
          'sendto' => $request->sendto,
          'note' => "Pengiriman barang siap jual dengan nomor surat ".$request->no_letter." tanggal ".$tanggal." ".$request->note,
          'user_id' => $user_id,
          'company_id' => $company,
        ];
       $sendid = send::create($send);

        $id_sku = $request->id_sku;
        $quantity = $request->quantity;
        $measure = $request->measuredetail;
        foreach ($id_sku as $key => $m )  {
            $inventorysku= [
                'id_sku' => $id_sku[$key],
                'in_out_qty' => -$quantity[$key],
                'measure' => $measure[$key],
                'lokasi' => $request->from,
                'arr_date' => $tanggal,
                'user_id' => $user_id,
                'company_id' => $company,
                'note' => "Pengiriman barang siap jual dengan nomor surat ".$request->no_letter." tanggal ".$tanggal,
            ];
            $invid=Inventorysku::create($inventorysku);
            $senddetail= [
                'skuproduct_id' => $id_sku[$key],
                'send_id' => $sendid->id,
                'quantity' => $quantity[$key],
                'measure' => $measure[$key],
                'jenis' => "2",
                'user_id' => $user_id,
                'company_id' => $company,
                'inventory_id' => $invid->id,
            ];
          //  dd($senddetail);
            senddetail::create($senddetail);

        }
	      flash()->success('Success', 'Pengiriman sudah di Buat');
        return redirect('send/'.$request->from);
    }

    public function sendbarangstore(Request $request)
    {
        $pesan = [
                  'required' => 'Ada kolom yang belum diisi.',
                  'unique' => 'Nomor surat, sudah dipakai.'
                ];
        $this->validate($request, [
                'no_letter' => [
                'required',
                Rule::unique('lerp_send')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })
                ],
                'sendto' => 'required',
                'quantity' => 'required',
                'letter_date' => 'required',
    	  ],$pesan);
        $date = DateTime::createFromFormat('d-m-Y', $request->letter_date);
        $tanggal = $date->format('Y-m-d');
        $company = Auth::user()->company_id;
        $user_id = Auth::user()->id;
        $send = [
          'no_letter' => $request->no_letter,
          'letter_date' => $tanggal,
          'from' => $request->from,
          'sendto' => $request->sendto,
          'note' => "Pengiriman barang dengan nomor surat ".$request->no_letter." tanggal ".$tanggal." ".$request->note,
          'user_id' => $user_id,
          'company_id' => $company,
        ];
       $sendid = send::create($send);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $subtotal =str_replace(",", "", $request->sub_total);
        $measure = $request->measure;
        foreach ($product_id as $key => $m )  {
            $inventory= [
                'id_product' => $product_id[$key],
                'in_out_qty' => -$quantity[$key],
                'measure' => $measure[$key],
                'gudang' => $request->fromid,
                'sub_total' => -$subtotal[$key],
                'arr_date' => $tanggal,
                'user_id' => $user_id,
                'company_id' => $company,
                'note' => "Pengiriman barang dengan nomor surat ".$request->no_letter." tanggal ".$tanggal,
            ];
            $invid=Inventory::create($inventory);
            $senddetail= [
                'skuproduct_id' => $product_id[$key],
                'send_id' => $sendid->id,
                'quantity' => $quantity[$key],
                'sub_total' => $subtotal[$key],
                'measure' => $measure[$key],
                'jenis' => "1",
                'user_id' => $user_id,
                'company_id' => $company,
                'inventory_id' => $invid->id,
            ];
          //  dd($senddetail);
            senddetail::create($senddetail);

        }
	      flash()->success('Success', 'Pengiriman sudah di Buat');
        return redirect('send/'.$request->from);
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
    public function sendedit($id)
    {
        $company = Auth::user()->company_id;

        $senddetail=senddetail::where('send_id',$id)->get();
        $sendjenis=senddetail::where('send_id',$id)->first();
        $send=send::find($id);
        $outlet = Outlet::where('company_id', $company)->where('outlet_code', '!=', $send->from)->get();
        $warehouse = Warehouse::where('company_id', $company)->where('warehouse_code', '!=', $send->from)->get();
        if(substr($send->from, 0, 2)=="WH"){
           $lokasi = Warehouse::where('company_id', $company)->where('warehouse_code', $send->from )->first();
        }else{
           $lokasi = Outlet::where('company_id', $company)->where('outlet_code', $send->from )->first();
        }
        if($sendjenis->jenis=="1"){
          return view('persediaan.send.sendeditbarang',compact('warehouse','senddetail','send','lokasi'));
        }else{
          return view('persediaan.send.sendeditsku',compact('warehouse','outlet','senddetail','send','lokasi'));
        }


    }

    public function sendupdate(Request $request, $id)
    {
         $pesan = [
                   'required' => 'Ada kolom yang belum diisi.',
                   'unique' => 'Nomor surat, sudah dipakai.'
                 ];
         $this->validate($request, [
                 'no_letter' => [
                 'required',
                 Rule::unique('lerp_send')->where(function ($query) {
                       $company = Auth::user()->company_id;
                       return $query->where('company_id', $company);
                   })->ignore($id),
                 ],
                 'sendto' => 'required',
                 'letter_date' => 'required',
     	  ],$pesan);
        $date = DateTime::createFromFormat('d-m-Y', $request->letter_date);
        $tanggal = $date->format('Y-m-d');
         $send = [
           'no_letter' => $request->no_letter,
           'letter_date' => $tanggal,
           'from' => $request->from,
           'sendto' => $request->sendto,
           'note' => "Pengiriman barang dengan nomor surat ".$request->no_letter." tanggal ".$tanggal." ".$request->note,
           'user_id' => Auth::user()->id,
           'company_id' => Auth::user()->company_id,
         ];

          send::find($id)->update($send);
          flash()->success('Success', 'Pengiriman Sudah Di Update');
          return redirect('send/'.$request->from);
    }


    public function sendskugen($id)
    {
        $year = date("Y");
        $results='';
        $resultsfrom='';
        $l=3;
        $company = Auth::user()->company_id;
        $company_name = Auth::user()->company->company_name;
        if(substr($id, 0, 2)=="WH"){
          $warehouse=Warehouse::where('company_id',$company)->where('warehouse_code',$id)->first();
          $name=$warehouse->warehouse_name;
        }else{
          $outlet=Outlet::where('company_id',$company)->where('outlet_code',$id)->first();
          $name=$outlet->outlet_name;
        }
        $vowels = array('A', 'E', 'I', 'O', 'U', 'Y'); // vowels
        preg_match_all('/[A-Z][a-z]*/', ucfirst($company_name), $m); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($m[0] as $substring){
            $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
            $results .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        preg_match_all('/[A-Z][a-z]*/', ucfirst($name), $k); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($k[0] as $substring){
            $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
            $resultsfrom .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        $id=send::where('company_id', $company )->where('from',$id)->whereYear('created_at', '=', $year)->count()+1;
        $hasil = 'KRM-'. str_pad($id, 4, 0, STR_PAD_LEFT)."-SKU/".$resultsfrom."/".$results."/".$year; // Add the ID
        echo json_encode($hasil);
    }
    public function sendgen($id)
    {
        $year = date("Y");
        $results='';
        $resultsfrom='';
        $l=3;
        $company = Auth::user()->company_id;
        $company_name = Auth::user()->company->company_name;
        if(substr($id, 0, 2)=="WH"){
          $warehouse=Warehouse::where('company_id',$company)->where('warehouse_code',$id)->first();
          $name=$warehouse->warehouse_name;
        }else{
          $outlet=Outlet::where('company_id',$company)->where('outlet_code',$id)->first();
          $name=$outlet->outlet_name;
        }
        $vowels = array('A', 'E', 'I', 'O', 'U', 'Y'); // vowels
        preg_match_all('/[A-Z][a-z]*/', ucfirst($company_name), $m); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($m[0] as $substring){
            $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
            $results .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        preg_match_all('/[A-Z][a-z]*/', ucfirst($name), $k); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($k[0] as $substring){
            $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
            $resultsfrom .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        $id=send::where('company_id', $company )->where('from',$id)->whereYear('created_at', '=', $year)->count()+1;
        $hasil = 'KRM-'. str_pad($id, 4, 0, STR_PAD_LEFT)."/".$resultsfrom."/".$results."/".$year; // Add the ID
        echo json_encode($hasil);
    }
    public function senddelete($id)
    {
        $sendjenis=senddetail::where('company_id',Auth::user()->company_id)->where('send_id', $id)->first();
        $senddetails=senddetail::where('company_id',Auth::user()->company_id)->where('send_id', $id)->get();
        $send=send::where('company_id',Auth::user()->company_id)->find($id);
        $from=$send->from;
          if($sendjenis->jenis=="1"){
            foreach ($senddetails as $senddetail )  {
              Inventory::where('company_id',Auth::user()->company_id)->where('id', $senddetail->inventory_id)->delete();
            }
          }else{
              Inventorysku::where('company_id',Auth::user()->company_id)->where('id', $senddetail->inventory_id)->delete();
          }
          senddetail::where('company_id',Auth::user()->company_id)->where('send_id', $id)->delete();
          send::where('company_id',Auth::user()->company_id)->where('id', $id)->delete();

        return redirect('send/'.$from);
    }


}
