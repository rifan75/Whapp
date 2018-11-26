<?php

namespace Modules\Purchase\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

use Modules\Purchase\Entities\Supplier;
use Modules\Purchase\Entities\Warehouse;
use Modules\Purchase\Entities\Purchase;
use Modules\Purchase\Entities\Purchasedetail;
use Modules\Purchase\Entities\Product;
use Modules\Purchase\Entities\Measure;
use Modules\Purchase\Entities\User;
use Hashids\Hashids;
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
	      return view('purchase::purchase');
    }

    public function getPurchase()
    {
        $purchases = Purchase::all();
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);

        $no = 0;
        $data = array();
        foreach ($purchases as $purchase) {
          $no ++;
          $row = array();
          $rowdetail = array();
          $row[] = $no;
          $row[] = $hashids->encode($purchase->id);
          $row[] = $purchase->invoice_id;
          if(count($purchase->supplier)==0){
            $row[] = "No Supplier/ Deleted";
          }else{
            $row[] = $purchase->supplier->name;
          }
          $row[] = $purchase->order_date->format('d-M-Y');
          $row['total'] = number_format($purchase->total);
          $row['note'] = $purchase->note;
          $row[] = $purchase->user->name;
          if($purchase->send_date){
            $row[] = $purchase->send_date->format('d-M-Y');
          }else{
            $row[] = "Not Send Yet";
          }
          $row[] = $purchase->payment;
          if ($purchase->total-$purchase->payment==0){
            $row[] = "Lunas";
          }else{
            $row[] = number_format($purchase->total-$purchase->payment);
          }
          if(count($purchase->warehouse)==0){
            $row[] = "No Warehouse/ Deleted";
          }else{
            $row[] = $purchase->warehouse->name;
          }
          $row[] = $purchase->imageinvoice_path;
          $row[] = "<a href='/purchase/".$hashids->encode($purchase->id)."/print' target='_blank'><i class='fa fa-download'></i></a>
                                      &nbsp;&nbsp;&nbsp;
                        <a href='/purchase/".$hashids->encode($purchase->id)."/edit'><i class='fa fa-pencil-square-o'></i></a>
                        &nbsp;&nbsp;&nbsp;
                      <a href='#' onclick='deleteForm(".$hashids->encode($purchase->id).")' type='submit'><i class='fa fa-trash'></i></a>";
          foreach ($purchase->purchasedetail as $detail) {

            $rowdetail['product'][] = $detail->product->name;
            $rowdetail['quantity'][] = $detail->quantity;
            $rowdetail['measure'][] = $detail->measure;
            $rowdetail['price'][] = number_format($detail->price);
            $rowdetail['subtotal'][] = number_format($detail->sub_total);

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
        $supplier = Supplier::all();
        $warehouse = Warehouse::all();
        return view('purchase::purchaseorder',compact('supplier','warehouse'));
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
        $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                    <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span></button>";
        $row[] = $product->user->name;
        $row[] = $url.'/'.$product->image_path['image1'];
        $row[] = $product->measure;
        $data[] = $row;
      }

      return DataTables::of($data)->escapeColumns([])->make(true);
     }

    public function purchasestore(Request $request)
    {
        $this->validate($request, [
                'invoice_id' => 'required|unique:purchase',
                'quantity' => 'required',
                'price' => 'required',
                'total' => 'required',
                'sub_total' => 'required',
    	  ]);

        $date = DateTime::createFromFormat('d-M-Y', $request->order_date);
        $tanggal = $date->format('Y-m-d');
        $total = str_replace(",", "", $request->total);
        $data = [
          'invoice_id' => $request->invoice_id,
          'supplier_id' => $request->supplier_id,
          'order_date' => $tanggal,
          'total' => $total,
          'note' => $request->note,
          'user_id' => Auth::user()->id,
          'sendto'=> $request->sendto,
        ];
        $purchase = Purchase::create($data);

        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);

        $product_id = $request->product_id;
        $quantity = $request->quantity;
        $measure = $request->measure;
        $price = $request->price;
        $subtotal = str_replace(",", "", $request->sub_total);
        foreach ($product_id as $key => $m )  {
            $purchasedetail= [
                'product_id' => $hashids->decode($m)[0],
                'purchase_id' => $purchase->id,
                'quantity' => $quantity[$key],
                'measure' => $measure[$key],
                'price' => $price[$key],
                'sub_total' => $subtotal[$key],
                'user_id' => Auth::user()->id,
            ];

            Purchasedetail::create($purchasedetail);
        }

        flash()->print('Success', 'Purchase Added');
        return redirect('purchase/order')->with('id',$purchase->hashid);
    }

    public function purchasegen()
    {
        $year = date("Y");
        $results='';
        $l=3;
        $code_name = "PURCHASE";
        $vowels = array('A', 'E', 'I', 'O', 'U', 'Y'); // vowels
        preg_match_all('/[A-Z][a-z]*/', ucfirst($code_name), $m); // Match every word that begins with a capital letter, added ucfirst() in case there is no uppercase letter
        foreach($m[0] as $substring){
            $substring = str_replace($vowels, '', strtoupper($substring)); // String to lower case and remove all vowels
            $results .= preg_replace('/([A-Z]{'.$l.'})(.*)/', '$1', $substring); // Extract the first N letters.
        }
        $id=Purchase::whereYear('created_at', '=', $year)->count()+1;
        $hasil = 'No -'. str_pad($id, 4, 0, STR_PAD_LEFT)."/".$results."/".$year; // Add the ID
        echo json_encode($hasil);
    }

    public function purchaseprint($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $purchase=Purchase::find($ids);
        $pdf=PDF::loadView('purchase::purchaseprint',compact('purchase'));
        return $pdf->stream($purchase->invoice_id."-invoice.pdf");

    }

    public function purchaseedit($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $supplier = Supplier::all();
        $warehouse = Warehouse::all();
        $purchasedetail=Purchasedetail::where('purchase_id',$ids)->get();
        $purchase=Purchase::find($ids);
        return view('purchase::purchaseedit',compact('supplier','warehouse','purchasedetail','purchase'));

    }

    public function purchaseupdate(Request $request, $id)
    {
         $hash = config('app.hash_key');
         $hashids = new Hashids($hash,20);
         $ids=$hashids->decode($id)[0];

         $this->validate($request, [
           'invoice_id' => [
               'required',
               Rule::unique('purchase')->ignore($ids),
           ],
           'quantity' => 'required',
           'price' => 'required',
           'total' => 'required',
           'sub_total' => 'required',
         ]);

         $date = DateTime::createFromFormat('d-M-Y', $request->order_date);
         $tanggal = $date->format('Y-m-d');

         $total = str_replace(",", "", $request->total);
         $data = [
           'invoice_id' => $request->invoice_id,
           'supplier_id' => $request->supplier_id,
           'order_date' => $tanggal,
           'total' => $total,
           'note' => $request->note,
           'user_id' => Auth::user()->id,
           'sendto'=> $request->sendto,
         ];

          $purchase = Purchase::find($ids);
          $purchase->update($data);

          Purchasedetail::where('purchase_id', $purchase->id)->forceDelete();

          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);

          $product_id = $request->product_id;
          $quantity = $request->quantity;
          $measure = $request->measure;
          $price = $request->price;
          $subtotal = str_replace(",", "", $request->sub_total);
          foreach ($product_id as $key => $m )  {
              $purchasedetail= [
                  'product_id' => $hashids->decode($m)[0],
                  'purchase_id' => $purchase->id,
                  'quantity' => $quantity[$key],
                  'measure' => $measure[$key],
                  'price' => $price[$key],
                  'sub_total' => $subtotal[$key],
                  'user_id' => Auth::user()->id,
              ];
              Purchasedetail::create($purchasedetail);
          }

          flash()->print('Success', 'Purchase Updated');
          return redirect('purchase')->with('id',$id);
    }

    public function purchasedelete($id)
    {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];
        Purchasedetail::where('purchase_id', $ids)->delete();
        Purchase::destroy($ids);

        flash()->success('Success', 'Purchase Deleted');
        return back();
    }


}
