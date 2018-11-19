<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use Modules\User\Entities\User;
use Modules\User\Entities\Profile;
use Modules\User\Entities\Country;
use Hashids\Hashids;
use DataTables;
use DateTime;
use Auth;
use Gate;

class ProfileController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          $profile=Profile::where('user_id', Auth::user()->id)->first();
          $url = config('app.url_images');
          return view('user::profile',compact('profile','url'));
      }

      public function edit()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $countries=Country::all();
          $url = config('app.url_images');
          return view('user::profileedit',compact('countries','url'));
      }

      public function getProfile()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $hash = config('app.hash_key');
          $profiles = Profile::all();
          $url = config('app.url_images');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($profiles as $profile) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($profile->id);
            $row[] = $hashids->encode($profile->user_id);
            $row[] = $profile->user->name;
            $row[] = $profile->user->email;
            $row[] = $profile->department;
            $row[] = $profile->hire_date;
            $row[] = $profile->birth_date;
            $row[] = $profile->gender;
            $row[] = $profile->address;
            $row[] = $profile->city;
            $row[] = $profile->state;
            $row[] = $profile->country;
            $row[] = $profile->phone;
            $row[] = $profile->pos_code;
            $row[] = $url.'/'.$profile->user->picture_path;
            $row[] = "<button class='btn btn-primary btn-xs' type='button' id='clicktabel'>
                    <span class='glyphicon glyphicon-share-alt' aria-hidden='true'></span></button>";
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function profileupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          $this->validate($request, [
            'department' => 'required',
            'hire_date' => 'required',
            'birth_date' => 'required',
            'gender' => 'required',
            'address'=> 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pos_code' => 'required|numeric',
            'phone' => 'required|numeric',
          ]);

          $hire_date_convert = DateTime::createFromFormat('d-M-Y', $request->hire_date);
          $hire_date = $hire_date_convert->format('Y-m-d');
          $birth_date_convert = DateTime::createFromFormat('d-M-Y', $request->birth_date);
          $birth_date = $birth_date_convert->format('Y-m-d');

          $profile = [
            'department' => $request->department,
            'hire_date' => $hire_date,
            'birth_date' => $birth_date,
            'gender' => $request->gender,
            'address'=> $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'pos_code' => $request->pos_code,
            'phone' => $request->phone,
            'recorder' => Auth::user()->id,
          ];

          Profile::find($ids)->update($profile);
          flash()->success('Success', 'Profile is Updated');
          return redirect('user/profileedit');
      }

}
