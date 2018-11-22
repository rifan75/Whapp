<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Modules\Master\Entities\Brand;
use Modules\Master\Entities\User;
use DataTables;
use Auth;
use Gate;

class BrandController extends Controller
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
          return view('master::brand');
      }

      public function getBrand()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $brands = Brand::all();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($brands as $brand) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($brand->id);
            $row[] = $brand->name;
            $row[] = $brand->user->name;
            $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($brand->id)."\")'><i class='fa fa-pencil-square-o'></i></a>
                          &nbsp;&nbsp;&nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($brand->id)."\")' type='submit'><i class='fa fa-trash'></i></a>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function brandstore(Request $request)
      {
          $this->validate($request, [
            'name' => 'required|unique:brand',
          ]);
          $data=[
            'name' => ucfirst($request->name),
            'user_id' => Auth::user()->id
          ];
          Brand::create($data);
          flash()->success('Success', 'New Brand Added');
          return redirect('master/brand');
      }

      public function brandedit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $brand=Brand::find($ids);

          $data = [
              'name' => $brand->name,
          ];
          echo json_encode($data);
      }

      public function brandupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          $this->validate($request, [
            'name' => ['required',
                        Rule::unique('brand')->ignore($ids),
                      ]
          ]);

          $data=[
            'name' => ucfirst($request->name),
            'user_id' => Auth::user()->id
          ];
          Brand::find($ids)->update($data);
          flash()->success('Success', 'Brand Updated');
          return redirect('master/brand');
      }

      public function branddelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          Brand::destroy($ids);
          flash()->success('Success', 'Brand is Deleted');
          return redirect('master/brand');
      }
}
