<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Entities\Supplier;
use App\User;
use DataTables;
use Auth;

class SupplierController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          $linkgeneral = config('app.general_link');
          $province = json_decode(file_get_contents($linkgeneral.'/provinsi'));
          return view('master_data.supplier',compact('province'));
      }

      public function getSupplier()
      {
          $company = Auth::user()->company_id;
          $supplier = Supplier::where('company_id', $company)->get();
          $province = json_decode(file_get_contents('http://inddata.test/allprovinsi'),true);
          $city = json_decode(file_get_contents('http://inddata.test/allkota'),true);
          $kecamatan = json_decode(file_get_contents('http://inddata.test/allcamat/'),true);
          $no = 0;
          $data = array();
          foreach ($supplier as $pemasok) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $pemasok->id;
            $row[] = $pemasok->supplier_name;
            $row[] = $pemasok->supplier_address.' '.$kecamatan[$pemasok->supplier_camat].' '.$city[$pemasok->supplier_kabkota]['tipe'].' '.$city[$pemasok->supplier_kabkota]['nama'].
            '<br>'.$province[$pemasok->supplier_provinsi].'-'.$pemasok->kodepos;
            $row[] = $pemasok->supplier_phone;
            $row[] = $pemasok->supplier_email;
            $row[] = $pemasok->contact_person;
            $row[] = $pemasok->user->name;
            $row[] = $pemasok->note;
            $row[] = "<a href='#' onclick='editForm(".$pemasok->id.")'><i class='fa fa-pencil-square-o' title='edit'></i></a>
                        &nbsp;
                      <a href='#' onclick='deleteForm(".$pemasok->id.")' type='submit'><i class='fa fa-trash' title='hapus'></i></a>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function supplierstore(Request $request)
      {
          $this->validate($request, [
            'supplier_name' => [
                'required',
                Rule::unique('lerp_supplier')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })
            ],
          ]);
          $request->merge(['supplier_name' => ucfirst($request->supplier_name)]);
          Supplier::create($request->all()+ ['user_id' => Auth::user()->id]+ ['company_id' => Auth::user()->company_id]);
          flash()->success('Success', 'Supplier Baru Sudah Di Input');
          return redirect('supplier');
      }

      public function supplieredit($id)
      {
          $supplier=Supplier::find($id);
          echo json_encode($supplier);
      }

      public function supplierupdate(Request $request, $id)
      {
          $pesan = [
                    'required' => 'Kolom ini, harus diisi.',
                    'unique' => 'Supplier ini, sudah ada.'
                  ];
          $this->validate($request, [
            'supplier_name' => [
                'required',
                Rule::unique('lerp_supplier')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      $user_id = User::where('company_id', $company)->pluck('id');
                      return $query->whereIn('user_id', $user_id);
                  })->ignore($request->id),
            ],
          ],$pesan);
          $request->merge(['supplier_name' => ucfirst($request->supplier_name)]);
          Supplier::find($id)->update($request->all()+ ['user_id' => Auth::user()->id]);
          flash()->success('Success', 'Supplier Sudah Di Update');
          return redirect('supplier');
      }

      public function supplierdelete($id)
      {
          Supplier::destroy($id);
          return redirect('supplier');
      }

      public function supplierapi()
      {
        	$supplier = Supplier::all();
        	return $supplier;
      }

}
