<?php

namespace Modules\User\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

use Modules\User\Entities\User;
use Hashids\Hashids;
use DataTables;
use Auth;
use DB;
use Gate;

class UserController extends Controller
{
      public function __construct()
      {
          $this->middleware('auth');

      }

      public function getUser()
      {
          if (!Gate::allows('isAdmin'))
          {
             return response()->view('error.404', [], 404);
          }
          $hash = config('app.hash_key');
          $users = User::all();
          $url = config('app.url_images');
          $hashids = new Hashids($hash,20);
          $no = 0;
          $data = array();
          foreach ($users as $user) {
            $no ++;
            $row = array();
            $row[] = $no;
            $row[] = $hashids->encode($user->id);
            $row[] = $user->name;
            $row[] = $user->email;
            $row[] = $user->levelin->name;
            $row[] = $url.'/'.$user->picture_path;
            if($user->level!="1"){
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($user->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>
                          &nbsp;
                        <a href='#' onclick='deleteForm(\"".$hashids->encode($user->id)."\")' type='submit'><i class='fa fa-trash' title='erase'></i></a>";
              if($user->active==1){
                $row[] = "<a href='#' onclick='editAct(\"".$hashids->encode($user->id)."\",\"".$user->active."\")'><i class='fa fa-check' title='edit'></i></a>";
              }else{
                $row[] = "<a href='#' onclick='editAct(\"".$hashids->encode($user->id)."\",\"".$user->active."\")'><i class='fa fa-ban' title='edit'></i></a>";
              }
            }else{
              $row[] = "<a href='#' onclick='editForm(\"".$hashids->encode($user->id)."\")'><i class='fa fa-pencil-square-o' title='edit'></i></a>";
              $row[] = "<i class='fa fa-check' title='owner always active'></i>";
            }
            $data[] = $row;
          }

          return DataTables::of($data)->escapeColumns([])->make(true);
      }

      public function useredit($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $user=User::find($ids);
          echo json_encode($user);
      }

      public function userupdate(Request $request, $id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];

          if($request->fieldname==1){
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$ids,
            ]);
            $user= [
              "name" => $request->name,
              "email" => $request->email,
            ];
          }else{
            $this->validate($request, [
                'name' => 'required|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$ids,
                'level' => 'required',
            ]);
            $user= [
              "name" => $request->name,
              "email" => $request->email,
              "level" => $request->level,
            ];
          };

          $old_picture=User::where('id',$ids)->first();
          $old_picture_path=$old_picture->picture_path;


          if ($request->hasFile('picture_path')) {
            if($old_picture_path=='users/images/picture.jpg' or $old_picture_path== null){
                $name = str_random(11)."_".$request->file('picture_path')->getClientOriginalName();
                Storage::disk('local')->putFileAs('users/images', $request->file('picture_path'),$name);
                $path = 'users/images/'.$name;
            }else{
                $name = str_random(11)."_".$request->file('picture_path')->getClientOriginalName();
                Storage::disk('local')->putFileAs('users/images',  $request->file('picture_path'),$name);
                Storage::disk('local')->delete($old_picture_path);
                $path = 'users/images/'.$name;
            }
          }else{
              $path= $old_picture_path;
          }

          User::find($ids)->update($user + ['picture_path' => $path]);
          flash()->success('Success', 'User is Updated');
          return redirect('user');
      }

      public function useractupdate($id,$act)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          if($act==0){
            User::find($ids)->update(['active' => 1]);
          }else{
            User::find($ids)->update(['active' => 0]);
          }
      }

      public function userdelete($id)
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $ids=$hashids->decode($id)[0];
          $path = User::find($ids);
          if($path->image_path!='users/images/picture.jpg'){
            Storage::disk('s3')->delete(Auth::user()->company_id.'/'.$path->picture_path);
          }
          User::destroy($ids);
          return redirect('user');
      }

      public function changepasswd()
      {
          $hash = config('app.hash_key');
          $hashids = new Hashids($hash,20);
          $user_id = $hashids->encode(Auth::user()->id);
          return view('user::changepass',compact('user_id'));
      }

      public function updatepasswd(Request $request,$id)
      {
        $hash = config('app.hash_key');
        $hashids = new Hashids($hash,20);
        $ids=$hashids->decode($id)[0];

        $this->validate($request, [
            'password' => 'required|min:6|confirmed',
            'oldpassword' => 'required|oldpassword:' . Auth::user()->password
        ]);
        $password=['password' => bcrypt($request->password)];
        User::find($ids)->update($password);
        flash()->success('Success', 'Password telah di rubah');
        return redirect('profile');
      }
}
