<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Supplier;
use App\Warehouse;
use App\Purchase;
use App\Purchasedetail;
use App\Product;
use App\Measure;
use App\User;
use DateTime;
use DataTables;
use Auth;
use PDF;
use File;
use DB;

class PurchaseController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
	      return view('purchase.purchase');
    }

    public function getPurchase()
    {
        $company = Auth::user()->company_id;
        $user_id = User::where('company_id', $company)->pluck('id');
        $purchases = Purchase::whereIn('user_id', $user_id )->get();

        $no = 0;
        $data = array();
        foreach ($purchases as $purchase) {
          $no ++;
          $row = array();
          $rowdetail = array();
          $row[] = $no;
          $row[] = $purchase->id;
          $row[] = $purchase->invoice_id;
          if(count($purchase->supplier)==0){
            $row[] = "Supplier Tidak Ada/ Terhapus";
          }else{
            $row[] = $purchase->supplier->supplier_name;
          }
          $row[] = $purchase->order_date->format('d-m-Y');
          $row['total'] = uang($purchase->total);
          $row[] = $purchase->note;
          $row[] = $purchase->user->name;
          if($purchase->send_date){
            $row[] = $purchase->send_date->format('d-m-Y');
          }else{
            $row[] = "Belum Terkirim";
          }
          $row[] = $purchase->payment;
          if ($purchase->total-$purchase->payment==0){
            $row[] = "Lunas";
          }else{
            $row[] = uang($purchase->total-$purchase->payment);
          }
          if(count($purchase->warehouse)==0){
            $row[] = "Gudang Tidak Ada/ Terhapus";
          }else{
            $row[] = $purchase->warehouse->warehouse_name;
          }
          $row[] = $purchase->imageinvoice_path;
          $row[] = "<a href='/purchase/".$purchase->id."/show'><i class='fa fa-download'></i></a>
                                      &nbsp;&nbsp;&nbsp;
                        <a href='/purchase/".$purchase->id."/edit'><i class='fa fa-pencil-square-o'></i></a>
                        &nbsp;&nbsp;&nbsp;
                      <a href='#' onclick='deleteForm(".$purchase->id.")' type='submit'><i class='fa fa-trash'></i></a>";
          foreach ($purchase->purchasedetail as $detail) {

            $rowdetail['product'][] = $detail->product->product_name;
            $rowdetail['quantity'][] = $detail->quantity;
            $rowdetail['measure'][] = $detail->measure;
            $rowdetail['price'][] = uang($detail->price);
            $rowdetail['subtotal'][] = uang($detail->sub_total);

          }
          $row['product'] = $rowdetail['product'];
          $row['quantity'] = $rowdetail['quantity'];
          $row['measure'] = $rowdetail['measure'];
          $row['price'] = $rowdetail['price'];
          $row['subtotal'] = $rowdetail['subtotal'];
        //  $row[] = $purchase->purchasedetail->measure;
          $data[] = $row;
        }

        return DataTables::of($data)->escapeColumns([])->make(true);
    }

    public function purchaseorder()
    {
        $company = Auth::user()->company_id;
        $user_id = User::where('company_id', $company)->pluck('id');
        $supplier = Supplier::whereIn('user_id', $user_id )->get();
        $warehouse = Warehouse::whereIn('user_id', $user_id )->get();
        return view('purchase.purchaseorder',compact('supplier','warehouse'));
    }

    public function purchasestore(Request $request)
    {
        $pesan = [
                  'required' => 'Ada kolom yang belum diisi.',
                  'unique' => 'Kode order, sudah dipakai.'
                ];
        $this->validate($request, [
                'invoice_id' => [
                'required',
                Rule::unique('lerp_purchase')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      $user_id = User::where('company_id', $company)->pluck('id');
                      return $query->whereIn('user_id', $user_id);
                  })
                ],
                'quantity' => 'required',
                'price' => 'required',
                'total' => 'required',
                'sub_total' => 'required',
    	  ],$pesan);
        $date = DateTime::createFromFormat('d-m-Y H:i:s', $request->order_date);
        $tanggal = $date->format('Y-m-d H:i:s');
        $total = str_replace(".", "", $request->total);
        $company = Auth::user()->company_id;
        $user_id = User::where('company_id', $company)->pluck('id');
        $purchase = [
          'invoice_id' => $request->invoice_id,
          'supplier_id' => $request->supplier_id,
          'order_date' => $tanggal,
          'total' => $total,
          'note' => $request->note,
          'user_id' => Auth::user()->id,
          'sendto'=> $request->sendto,
        ];
        $purchaseid = Purchase::create($purchase);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $measure = $request->measure;
        $price = $request->price;
        $subtotal = str_replace(".", "", $request->sub_total);
        foreach ($product_id as $key => $m )  {
            $purchasedetail= [
                'product_id' => $product_id[$key],
                'purchase_id' => $purchaseid->id,
                'quantity' => $quantity[$key],
                'measure' => $measure[$key],
                'price' => $price[$key],
                'sub_total' => $subtotal[$key],
                'user_id' => Auth::user()->id,
            ];
            Purchasedetail::create($purchasedetail);
        }
	      flash()->success('Success', 'Order Pembelian sudah di Buat');
        return redirect('purchase');
    }

    public function purchaseshow($id)
    {
        $company = Auth::user()->company_id;
        $user_id = User::where('company_id', $company)->pluck('id');
        $purchase=Purchase::find($id);
        $pdf=PDF::loadView('purchase.purchaseshow',compact('purchase'));
        return $pdf->download($purchase->invoice_id."-invoice.pdf");

    }

    public function purchaseedit($id)
    {
        $company = Auth::user()->company_id;
        $user_id = User::where('company_id', $company)->pluck('id');
        $supplier = Supplier::whereIn('user_id', $user_id )->get();
        $warehouse = Warehouse::whereIn('user_id', $user_id )->get();
        $purchasedetail=Purchasedetail::where('purchase_id',$id)->get();
        $purchase=Purchase::find($id);
        return view('purchase.purchaseedit',compact('supplier','warehouse','purchasedetail','purchase'));

    }

    public function purchaseupdate(Request $request, $id)
    {
         $pesan = [
                   'required' => 'Kolom ini, harus diisi.',
                   'unique' => 'Nama Barang ini, sudah ada.'
                 ];
         $this->validate($request, [
             'invoice_id' => [
                 'required',
                 Rule::unique('lerp_purchase')->where(function ($query) {
                       $company = Auth::user()->company_id;
                       $user_id = User::where('company_id', $company)->pluck('id');
                       return $query->whereIn('user_id', $user_id);
                   })->ignore($request->id),
             ],
             'quantity' => 'required',
             'price' => 'required',
             'total' => 'required',
             'sub_total' => 'required',
         ],$pesan);
         $date = DateTime::createFromFormat('d-m-Y H:i:s', $request->order_date);
         $tanggal = $date->format('Y-m-d H:i:s');
         $total = str_replace(".", "", $request->total);
         $company = Auth::user()->company_id;
         $user_id = User::where('company_id', $company)->pluck('id');
         $purchase = [
           'invoice_id' => $request->invoice_id,
           'supplier_id' => $request->supplier_id,
           'order_date' => $tanggal,
           'total' => $total,
           'note' => $request->note,
           'user_id' => Auth::user()->id,
           'sendto'=> $request->sendto,
         ];

          Purchase::find($id)->update($purchase);
          $purchaseid = Purchase::find($id);
          Purchasedetail::where('purchase_id', $purchaseid->id)->delete();

          $product_id = $request->product_id;
          $quantity = $request->quantity;
          $measure = $request->measure;
          $price = $request->price;
          $subtotal = str_replace(".", "", $request->sub_total);
          foreach ($product_id as $key => $m )  {
              $purchasedetail= [
                  'product_id' => $product_id[$key],
                  'purchase_id' => $purchaseid->id,
                  'quantity' => $quantity[$key],
                  'measure' => $measure[$key],
                  'price' => $price[$key],
                  'sub_total' => $subtotal[$key],
                  'user_id' => Auth::user()->id,
              ];
              Purchasedetail::create($purchasedetail);
          }
          flash()->success('Success', 'Order Pembelian Sudah Di Update');
          return redirect('purchase');
    }

    public function purchasedelete($id)
    {
        Purchasedetail::where('purchase_id', $id)->delete();
        Purchase::destroy($id);
        return redirect('purchase');
    }


}
