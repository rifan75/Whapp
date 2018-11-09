<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Modules\Entities\Measure;
use App\User;
use DataTables;
use Auth;
use Gate;

class MeasureController extends Controller
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
          return view('master_data.measure');
      }

      public function getMeasure()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $company = Auth::user()->company_id;
          $measure = Measure::where('company_id', $company)->get();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($measure as $satuan) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($satuan->id);
            $row[] = $satuan->measure_name;
            $row[] = $satuan->user->name;
            $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($satuan->id)."\")'><i class='fa fa-pencil-square-o'></i></a>
                          &nbsp;&nbsp;&nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($satuan->id)."\")' type='submit'><i class='fa fa-trash'></i></a>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function measurestore(Request $request)
      {
          $company_id = Auth::user()->company_id;
          $pesan = [
                    'required' => 'Kolom ini, harus diisi.',
                    'unique' => 'Satuan ini, sudah ada.'
                  ];
          $this->validate($request, [
            'measure_name' => [
                'required',
                Rule::unique('lerp_measure')->where(function ($query) {
                      $company_id = Auth::user()->company_id;
                      return $query->where('company_id', $company_id);
                  })
            ],
          ],$pesan);
          $request->merge(['measure_name' => ucfirst($request->measure_name)]);
          Measure::create($request->all()+['company_id'=>$company_id]+['user_id' => Auth::user()->id]);
          flash()->success('Success', 'Satuan Baru Sudah Di Input');
          return redirect('measure');
      }

      public function measureedit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $measure=Measure::find($ids);
          echo json_encode($measure);
      }

      public function measureupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $pesan = [
                    'required' => 'Kolom ini, harus diisi.',
                    'unique' => 'Satuan ini, sudah ada.'
                  ];
          $this->validate($request, [
            'measure_name' => [
                'required',
                Rule::unique('lerp_measure')->where(function ($query) {
                      $company = Auth::user()->company_id;
                      return $query->where('company_id', $company);
                  })
            ],
          ],$pesan);
          $request->merge(['measure_name' => ucfirst($request->measure_name)]);
          Measure::find($ids)->update($request->all());
          flash()->success('Success', 'Satuan Sudah Di Update');
          return redirect('measure');
      }

      public function measuredelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          Measure::destroy($ids);
          return redirect('measure');
      }
}
