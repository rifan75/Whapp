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
          $users=User::all();
          return view('master::warehouse',compact('countries','users'));
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
              '<br> Incharge : '.$warehouse->inchargedata->name;
            }else{
              $row[] = '';
            }
            $row[] = $warehouse->user->name;
            $row[] = $warehouse->note;
            if($count == 1){
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

          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($request->incharge)[0];
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
            'incharge' => $ids,
            'note' => $request->note,
            'user_id' => Auth::user()->id
          ];

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

          $data = [
            'code' => $warehouse->code,
            'name' => $warehouse->name,
            'address' => $warehouse->address,
            'city' => $warehouse->city,
            'state' => $warehouse->state,
            'country' => $warehouse->country,
            'pos_code' => $warehouse->pos_code,
            'phone' => $warehouse->phone,
            'email' => $warehouse->email,
            'incharge' => $warehouse->hashincharge,
            'note' => $warehouse->note,
            'active' => $warehouse->active,
          ];

          echo json_encode($data);
      }

      public function warehouseupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $idincharge=$hashids->decode($request->incharge)[0];

          $this->validate($request, [
            'name' => [
                'required',
                 Rule::unique('warehouse')->ignore($ids),
            ],
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
            'name' => ucfirst($request->name),
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pos_code' => $request->pos_code,
            'phone' => $request->phone,
            'email' => $request->email,
            'incharge' => $idincharge,
            'note' => $request->note,
            'user_id' => Auth::user()->id
          ];

          Warehouse::find($ids)->update($data);
          flash()->success('Success', 'warehouse is Updated');
          return redirect('master/warehouse');
      }

      public function warehousedelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          Warehouse::destroy($ids);
          flash()->success('Success', 'Warehouse Deleted');
          return redirect('master/warehouse');
      }

}
