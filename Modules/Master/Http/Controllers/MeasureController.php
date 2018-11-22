<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Hashids\Hashids;
use Modules\Master\Entities\Measure;
use Modules\Master\Entities\User;
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
          return view('master::measure');
      }

      public function getMeasure()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $measures = Measure::all();
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($measures as $measure) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($measure->id);
            $row[] = $measure->name;
            $row[] = $measure->user->name;
            $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($measure->id)."\")'><i class='fa fa-pencil-square-o'></i></a>
                          &nbsp;&nbsp;&nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($measure->id)."\")' type='submit'><i class='fa fa-trash'></i></a>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function measurestore(Request $request)
      {
          $this->validate($request, [
            'name' => 'required|unique:measure',
          ]);
          $data=[
            'name' => ucfirst($request->name),
            'user_id' => Auth::user()->id
          ];
          Measure::create($data);
          flash()->success('Success', 'New Measure Added');
          return redirect('master/measure');
      }

      public function measureedit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $measure=Measure::find($ids);

          $data = [
            'name' => $measure->name,
          ];
          echo json_encode($data);
      }

      public function measureupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $this->validate($request, [
            'name' => ['required',
                        Rule::unique('measure')->ignore($ids),
                      ]
          ]);

          $data=[
            'name' => ucfirst($request->name),
            'user_id' => Auth::user()->id
          ];

          Measure::find($ids)->update($data);
          flash()->success('Success', 'Measure Updated');
          return redirect('master/measure');
      }

      public function measuredelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          Measure::destroy($ids);
          flash()->success('Success', 'Measure Deleted');
          return redirect('master/measure');
      }
}
