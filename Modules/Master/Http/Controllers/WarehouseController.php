<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Master\Entities\Warehouse;
use Modules\Master\Entities\Country;
use Hashids\Hashids;
use Modules\Master\Entities\User;
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
          $countries=Country::all();
          return view('master::warehouse',compact('countries'));
      }

      public function getWarehouse()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $warehouses = Warehouse::all();
          $count = Warehouse::count();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($warehouses as $warehouse) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($warehouse->id);
            $row[] = $warehouse->name;
            if($warehouse->address != null){
              $row[] = $warehouse->address.' '.$warehouse->city.' '.$warehouse->state.
              '<br>'.$warehouse->country.'-'.$warehouse->pos_code.
              '<br> Email : '.$warehouse->email.
              '<br> Phone : '.$warehouse->phone.
              '<br> Contact Person : '.$warehouse->incharge->name;
            }else{
              $row[] = '';
            }
            $row[] = $warehouse->user->name;
            $row[] = $warehouse->note;
            if($count = 1){
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($warehouse->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>";
            }else{
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($warehouse->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>
                          &nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($warehouse->id)."\")' type='submit'><i class='fa fa-trash' title='hapus'></i></a>";
            }
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function warehousestore(Request $request)
      {
          $this->validate($request, [
            'name' => 'required|unique:warehouse',
            'address'=> 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pos_code' => 'required|numeric',
            'phone' => 'required',
            'email' => 'required|email',
            'incharge' => 'required',
          ]);
          $data=[
            'code' => "WH".uniqid(),
            'name' => ucfirst($request->name),
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pos_code' => $request->pos_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'incharge' => $request->incharge,
            'note' => $request->note,
            'user_id' => Auth::user()->id
          ];
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($request->incharge)[0];

          Warehouse::create($data);
          flash()->success('Success', 'New Warehouse Added');
          return redirect('master/warehouse');
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
                    'unique' => 'warehouse ini, sudah ada.'
                  ];
          $this->validate($request, [
            'warehouse_name' => [
                'required',
                Rule::unique('lerp_warehouse')->where(function ($query) {
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
          flash()->success('Success', 'warehouse Sudah Di Update');
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
