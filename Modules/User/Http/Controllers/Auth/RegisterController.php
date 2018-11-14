<?php

namespace Modules\Master\Http\Controllers\Auth;

use Modules\Master\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Storage;

use Modules\Master\Entities\User;
use Modules\Master\Entities\Level;
use Hashids\Hashids;
use Auth;
use Gate;
use DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/master/user';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
     public function __construct()
     {
         $this->middleware('auth');
     }

     public function showRegistrationForm()
     {
         if (!Gate::allows('isAdmin')) {

            return response()->view('error.404', [], 404);
         }
         $levels=Level::all();
         return view('master::user',compact('levels'));
     }
     /**
      * Get a validator for an incoming registration request.
      *
      * @param  array  $data
      * @return \Illuminate\Contracts\Validation\Validator
      */
     protected function validator(array $data)
     {
         return Validator::make($data, [
           'name' => 'required|max:255',
           'email' => 'required|email|max:255|unique:users',
           'password' => 'required|min:6|confirmed',
           'level' => 'required',
           'picture_path' => 'mimes:jpeg,bmp,png'
         ]);
     }

     /**
      * Create a new user instance after a valid registration.
      *
      * @param  array  $data
      * @return User
      */
      protected function create(array $data)
      {
        if (isset($data['picture_path'])) {
              $name = str_random(11)."_".$data['picture_path']->getClientOriginalName();
              Storage::disk('local')->putFileAs('users/images', $data['picture_path'],$name);
              $path = 'users/images/'.$name;
          }else{
              $path= 'users/images/picture.jpg';
          }

          $datauser = [
              'name' => $data['name'],
              'email' => $data['email'],
              'level' => $data['level'],
              "picture_path" => $path,
              'active' => '1',
              'password' => bcrypt($data['password']),
          ];

          DB::transaction(function () use ($datauser){
            $userid=User::create($datauser);
            DB::insert('insert into user_detail (user_id) values (?)', [$userid->id]);
          });

          flash()->success('Success', 'User is Added');
          return Auth::user();
      }
}
