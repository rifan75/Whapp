<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Modules\Master\Entities\Supplier;
use Modules\Master\Entities\Country;
use Hashids\Hashids;
use Modules\Master\Entities\User;
use DataTables;
use Auth;
use Gate;

class SupplierController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          if (!Gate::allows('isStaff'))
          {
             return response()->view('error.404', [], 404);
          }
          $countries=Country::all();
          return view('master::supplier',compact('countries'));
      }

      public function getSupplier()
      {
          if (!Gate::allows('isStaff'))
          {
             return response()->view('error.404', [], 404);
          }
          $suppliers = Supplier::all();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($suppliers as $supplier) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($supplier->id);
            $row[] = $supplier->name;
            $row[] = $supplier->address.' '.$supplier->city.' '.$supplier->state.
            '<br>'.$supplier->country.'-'.$supplier->pos_code.
            '<br> Email : '.$supplier->email.
            '<br> Phone : '.$supplier->phone.
            '<br> Contact Person : '.$supplier->contact_person;
            $row[] = $supplier->user->name;
            $row[] = $supplier->note;
            $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($supplier->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>
                        &nbsp;
                      <a href='#' onclick='deleteForm(\"".$hashids->encode($supplier->id)."\")' type='submit'><i class='fa fa-trash' title='hapus'></i></a>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function supplierstore(Request $request)
      {
          $this->validate($request, [
            'name' => 'required|unique:supplier',
            'address'=> 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pos_code' => 'required|numeric',
            'phone' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
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
            'contact_person' => $request->contact_person,
            'note' => $request->note,
            'user_id' => Auth::user()->id
          ];
          Supplier::create($data);
          flash()->success('Success', 'New Supplier Added');
          return redirect('master/supplier');
      }

      public function supplieredit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $supplier=Supplier::find($ids);

          $data = [
            'name' => $supplier->name,
            'address' => $supplier->address,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'country' => $supplier->country,
            'pos_code' => $supplier->pos_code,
            'phone' => $supplier->phone,
            'email' => $supplier->email,
            'contact_person' => $supplier->contact_person,
            'note' => $supplier->note,
            'active' => $supplier->active,
          ];

          echo json_encode($data);
      }

      public function supplierupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          $this->validate($request, [
            'name' => ['required',
                        Rule::unique('supplier')->ignore($ids),
                      ],
            'address'=> 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pos_code' => 'required|numeric',
            'phone' => 'required',
            'email' => 'required|email',
            'contact_person' => 'required',
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
            'contact_person' => $request->contact_person,
            'note' => $request->note,
            'user_id' => Auth::user()->id
          ];

          Supplier::find($ids)->update($data);
          flash()->success('Success', 'Supplier Updated');
          return redirect('master/supplier');
      }

      public function supplierdelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          Supplier::destroy($ids);
          flash()->success('Success', 'Supplier Deleted');
          return redirect('master/supplier');
      }
}
