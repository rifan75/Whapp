<?php

namespace Modules\Master\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use App\User;
use Modules\Entities\Profile;
use DataTables;
use Auth;

class ProfileController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');
      }

      public function index()
      {
          $profile=Profile::where('user_id', Auth::user()->id)->first();
          $url = config('app.s3_images');
          $company = Auth::user()->company_id;
          $user_id = User::where('company_id', $company)->get();
          $sendto = Message::where('from',Auth::user()->id)->get();
          return view('hrm.profile',compact('user_id','profile','url','sendto'));
      }

      public function profileupdate(Request $request, $id)
      {
          $profile= $request->all();
          Profile::find($id)->update($profile);
          flash()->success('Success', 'User is Updated');
          return redirect('profile');
      }

}
