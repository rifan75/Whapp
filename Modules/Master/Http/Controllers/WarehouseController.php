<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Modules\Entities\Warehouse;
use App\User;
use DataTables;
use Auth;
use Gate;

class WarehouseController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $company = Auth::user()->company_id;
          $linkgeneral = config('app.general_link');
          $province = json_decode(file_get_contents($linkgeneral.'/provinsi'));
          $user_id = User::where('company_id', $company)->get();
          return view('master_data.warehouse',compact('user_id','province'));
      }

      public function getWarehouse()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $company = Auth::user()->company_id;
          $warehouse = Warehouse::where('company_id', $company )->get();
          $linkgeneral = config('app.general_link');
          $hash = config('app.hash_key');
          $province = json_decode(file_get_contents($linkgeneral.'/allprovinsi'),true);
          $city = json_decode(file_get_contents($linkgeneral.'/allkota'),true);
          $kecamatan = json_decode(file_get_contents($linkgeneral.'/allcamat/'),true);
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($warehouse as $gudang) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($gudang->id);
            $row[] = $gudang->warehouse_name;
            if($gudang->warehouse_address != null){
              $row[] = $gudang->warehouse_address.' '.$kecamatan[$gudang->warehouse_camat].' '.$city[$gudang->warehouse_kabkota]['tipe'].' '.$city[$gudang->warehouse_kabkota]['nama'].
              '<br>'.$province[$gudang->warehouse_provinsi].'-'.$gudang->kodepos;
            }else{
              $row[] = '';
            }
            $row[] = $gudang->warehouse_phone;
            $row[] = $gudang->warehouse_email;
            $row[] = $gudang->penanggung->name;
            $row[] = $gudang->user->name;
            $row[] = $gudang->note;
            if($gudang->warehouse_code == 'WH1'){
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($gudang->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>";
            }else{
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($gudang->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>
                          &nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($gudang->id)."\")' type='submit'><i class='fa fa-trash' title='hapus'></i></a>";
            }
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function warehousestore(Request $request)
      {
          $pesan = [
                    'required' => 'Kolom ini, harus diisi.',
                    'unique' => 'Gudang ini, sudah ada.'
                  ];
          $this->validate($request, [
            'warehouse_name' => [
                'required',
                Rule::unique('lerp_gudang')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })
            ],
            'warehouse_address' => 'required',
          ],$pesan);
          $request->merge(['warehouse_name' => ucfirst($request->warehouse_name)]);
          $warehouse_code = "WH".uniqid();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($request->incharge)[0];
          $inputwh=[
              'warehouse_name' => $request->warehouse_name,
              'warehouse_address' => $request->warehouse_address,
              'warehouse_provinsi' => $request->warehouse_provinsi,
              'warehouse_kabkota' => $request->warehouse_kabkota,
              'warehouse_camat' => $request->warehouse_camat,
              'kodepos' => $request->kodepos,
              'warehouse_phone' => $request->warehouse_phone,
              'warehouse_email' => $request->warehouse_email,
              'incharge' => $ids,
              'note' => $request->note,
              'warehouse_code' => $warehouse_code,
              'company_id' => Auth::user()->company_id,
              'user_id' => Auth::user()->id,
          ];
          Warehouse::create($inputwh);
          flash()->success('Success', 'Gudang Baru Sudah Di Input');
          return redirect('warehouse');
      }

      public function warehouseedit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $warehouse=Warehouse::find($ids);
          echo json_encode($warehouse);
      }

      public function warehouseupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $idincharge=$hashids->decode($request->incharge)[0];
          $pesan = [
                    'required' => 'Kolom ini, harus diisi.',
                    'unique' => 'Gudang ini, sudah ada.'
                  ];
          $this->validate($request, [
            'warehouse_name' => [
                'required',
                Rule::unique('lerp_gudang')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })->ignore($ids),
            ],
            'warehouse_address' => 'required',
          ],$pesan);
          $request->merge(['warehouse_name' => ucfirst($request->warehouse_name)]);
          $inputwh=[
              'warehouse_name' => $request->warehouse_name,
              'warehouse_address' => $request->warehouse_address,
              'warehouse_provinsi' => $request->warehouse_provinsi,
              'warehouse_kabkota' => $request->warehouse_kabkota,
              'warehouse_camat' => $request->warehouse_camat,
              'kodepos' => $request->kodepos,
              'warehouse_phone' => $request->warehouse_phone,
              'warehouse_email' => $request->warehouse_email,
              'incharge' => $idincharge,
              'note' => $request->note,
              'user_id' => Auth::user()->id,
          ];
          Warehouse::find($ids)->update($inputwh);
          flash()->success('Success', 'Gudang Sudah Di Update');
          return redirect('warehouse');
      }

      public function warehousedelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          Warehouse::destroy($ids);
          return redirect('warehouse');
      }

}
