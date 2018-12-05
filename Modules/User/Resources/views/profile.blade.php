@extends('adminlte::page')

@section('title', 'WApp|Profile')

{{-- page level styles --}}
@section('css')
<style>
.box-header {background-color: #222d32;}
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
</style>
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
			<div class="row">
				 <div class="box">
					 <div class="box-header">
						 <h3 class="box-title">Hello, {{$profile->user->name}}</h3>
					 </div>
					 <div class="panel-body">
						 <div class="row">
						  <div class="col-md-12">

							 <div id="image"> <img alt="User Pic" align="center" src="{{$url}}/{{Auth::user()->picture_path}}" class="img-responsive" height="200" width="200">
							 </div>
							 <br>

									 <form id="formeditprofile" class="form-horizontal" role="form"  method="post" action="/profile/{{ $profile->id }}/edit">
										 	{{csrf_field()}}
											<input id="inputhidden" type='hidden' name='_method' value='PATCH'>
								 <table class="table table-user-information">
									 <tbody>
										 <tr id="departmentrow">
											 <td width="30%">Department</td>
											 <td id="department">{{$profile->department}}</td>
										 </tr>
										 <tr id="hire_daterow">
											 <td width="30%">Hire Date</td>
											 <td id="hire_date">{{$profile->hire_date}}</td>
										 </tr>
										 <tr id="birth_daterow">
											 <td width="30%">Birth Date</td>
											 <td id="birth_date">{{$profile->birth_date}}</td>
										 </tr>
										 <tr id="genderrow">
											 <td width="30%">Gender</td>
											 <td id="gender">{{$profile->gender}}</td>
										 </tr>
										 <tr id="addressrow">
											 <td width="30%">Address</td>
											 <td id="address">
												 {{$profile->home_address}}
												 {{$profile->home_district}}
												 {{$profile->home_city}}
												 {{$profile->home_province}}
												 {{$profile->pos_code}}
											 </td>
										 </tr>
										 <tr id="emailrow">
											 <td width="30%">Email</td>
											 <td id="email">{{$profile->user->email}}</td>
										 </tr>
										 <tr id="phonerow">
											 <td width="30%">Phone</td>
											 <td id="phone">{{$profile->phone}}</td>
										 </tr>
										 <tr id="phonerow">
											 <td width="30%">Recorder</td>
											 <td id="phone">{{$profile->recorderdata->name}}</td>
										 </tr>
									 </tbody>


								 </table>
								 </form>
                </div>
							 </div>
						 </div>
					 </div>

		 </div>
    </div>

  </div>
</section>
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript">



</script>
@include('flash')

@stop
