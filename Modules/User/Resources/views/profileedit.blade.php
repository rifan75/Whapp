@extends('adminlte::page')

@section('title', 'WApp|Profile')

@section('css')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
				 <div class="box">
					 <div class="panel-body">
						 <div class="row">
							 <div class="col-md-3">
                     <table id="profiletable" class="table table-hover">
                       <thead style="vertical-align:middle">
                         <tr bgcolor="#D3D3D3" >
                           <th ></th>
                           <th ></th>
                           <th style="text-align:center">Name</th>
                           <th style="text-align:center"></th>
                         </tr>
                       </thead>
                       <tbody>
                       </tbody>
                     </table>
                </div>
							  <div class="col-md-9">
									<form id="formeditprofile" class="form-horizontal" role="form"  method="post" action="/profile/edit">
											 	{{csrf_field()}}
												<input id="inputhidden" type='hidden' name='_method' value='PATCH'>
										<div class="form-group col-md-12">
													<div class="form-group col-md-6">
														<img alt="User Picture" id="image" align="center" src="{{$url}}/users/images/picture.jpg" class="img-responsive" height="200" width="200">
					 							 </div>
												 <div class="form-group col-md-5" id="userdata">
													 <label for="name" class=" control-label">Name :</label><p id="name"></p>
													 <label for="email" class=" control-label">Email :</label><p id="email"></p>
												 </div>
			              </div>
										<div class="form-group col-md-12">
			              	<label for="department" class=" control-label">Department</label>
			              	<input id="department" type="text" class="form-control" name="department" value="{{ old('department') }}"  required>
											@if ($errors->has('department'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('department')}}</strong>
												</span>
											@endif
			              </div>
										<div class="form-group col-md-12">
											<div class="form-group">
					              <label for="hire_date" class="col-sm-3 control-label" style="text-align:left">Hire Date : </label>
					              <div class="col-sm-5">
					              <div class='input-group date'>
					                  <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
					                  <input type='text' class="form-control"  name="hire_date" id="hire_date" value="{{ old('hire_date') }}" required/>
					              </div>
					              </div>
            					</div>
											@if ($errors->has('hire_date'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('hire_date')}}</strong>
												</span>
											@endif
			              </div>
										<div class="form-group col-md-12">
											<div class="form-group">
					              <label for="birth_date" class="col-sm-3 control-label" style="text-align:left">Birth Date : </label>
					              <div class="col-sm-5">
					              <div class='input-group date'>
					                  <div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
					                  <input type='text' class="form-control"  name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required/>
					              </div>
					              </div>
            					</div>
											@if ($errors->has('birth_date'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('birth_date')}}</strong>
												</span>
											@endif
			              </div>
                    <div class="form-group col-md-12">
                      <label for="gender" class=" control-label">Gender :</label><br>
											<label class="radio-inline">
											  <input type="radio" name="gender" id="gender1" value="Male" checked>
											    Male
                      </label>
											<label class="radio-inline">
											  <input type="radio" name="gender" id="gender2" value="Female">
											    Female
										  </label>
                    </div>
										<div class="form-group col-md-12">
			              	<label for="address" class=" control-label">Address</label>
			              	<textarea id="address"  cols="30" rows="2" class="form-control" name="address">{{ old('address') }}</textarea>
											@if ($errors->has('address'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('address')}}</strong>
												</span>
											@endif
			              </div>
										<div class="form-group col-md-12">
			              	<label for="city" class=" control-label">City</label>
			              	<input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
											@if ($errors->has('city'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('city')}}</strong>
												</span>
											@endif
			              </div>
										<div class="form-group col-md-12">
			              	<label for="state" class=" control-label">State</label>
			              	<input id="state" type="text" class="form-control" name="state"  required>
											@if ($errors->has('state'))
												<span class="help-block" style="color:red">
												<strong>{{ $errors->first('state')}}</strong>
												</span>
											@endif
			              </div>
										<div class="form-group col-md-12">
											<label for="country">Country</label>
											<select name="country" id="country" class="form-control">
												@foreach($countries as $country)
												<option value="{{ $country->value }}" id="{{$country->value}}">
												{{ $country->value }}
												</option>
												@endforeach
											</select>
											<p style="color:red">{{ $errors->first('country') }}</p>
										</div>
                    <div class="form-group col-md-12">
                      <div class="row" style="margin-left:1px">
                        <div class="form-group col-md-6" style="margin-right:10px">
                        <label for="pos_code" class=" control-label">Pos Code</label>
  			              	<input id="pos_code" type="text" class="form-control" name="pos_code"  required>
  											@if ($errors->has('pos_code'))
  												<span class="help-block" style="color:red">
  												<strong>{{ $errors->first('pos_code')}}</strong>
  												</span>
  											@endif
                      </div>
                      <div class="form-group col-md-6">
                        <label for="phone" class=" control-label">Phone</label>
                        <input id="phone" type="text" class="form-control" name="phone"  required>
                        @if ($errors->has('phone'))
                          <span class="help-block" style="color:red">
                          <strong>{{ $errors->first('phone')}}</strong>
                          </span>
                        @endif
                      </div>
                    </div>
			              </div>
										<div class="form-group col-md-12">
			              	<label for="note" class=" control-label">Note : </label>
			              	<textarea id="note"  cols="30" rows="2" class="form-control" name="note" value="{{ old('note') }}"></textarea>
			                <p style="color:red">{{ $errors->first('note') }}</p>
			              </div>
										<div class="form-group col-md-12">
										<input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Change Profile">
										</div>
									 </form>
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>@{{4}}</td></tr>
        <tr><td><image src="@{{15}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var table = $('#profiletable').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false,
    pageLength: 10,
    pagingType: "simple",
    ajax: {"url" : "/user/getprofile"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, visible: false},
        {data: 3, orderable: false},
        {data: 16, orderable: false},
    ],
        order: [0, 'desc'],
});

$('#profiletable tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row( tr );
    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row
        row.child( template(row.data()) ).show();
        tr.addClass('shown');
    }
});

$('#profiletable tbody').on( 'click', '#clicktabel', function () {
    var id = table.row($(this).closest('tr')).data()[1];
    var user_id = table.row($(this).closest('tr')).data()[2];
    var name = table.row($(this).closest('tr')).data()[3];
    var email = table.row($(this).closest('tr')).data()[4];
		var department = table.row($(this).closest('tr')).data()[5];
    var hire_date = table.row($(this).closest('tr')).data()[6];
    var birth_date = table.row($(this).closest('tr')).data()[7];
    var gender = table.row($(this).closest('tr')).data()[8];
    var address = table.row($(this).closest('tr')).data()[9];
    var city = table.row($(this).closest('tr')).data()[10];
    var state = table.row($(this).closest('tr')).data()[11];
		var country = table.row($(this).closest('tr')).data()[12];
    var phone = table.row($(this).closest('tr')).data()[13];
    var pos_code = table.row($(this).closest('tr')).data()[14];
		var picture = table.row($(this).closest('tr')).data()[15];
    $('#name').text(name);
    $('#email').text(email);
    $('#department').val(department);
    $('#hire_date').val(hire_date);
    $('#birth_date').val(birth_date);
    $('#gender').val(gender);
    $('#address').val(address);
		$('#city').val(address);
		$('#state').val(address);
		$('#country').val(address);
		$('#country').val(phone);
		$('#country').val(pos_code);
		$('#image').attr('src', picture);
		$('#formeditprofile').attr('action', '/user/profile/'+id+'/edit');
} );

$(function () {
        $('#hire_date').datetimepicker({
          format: 'DD-MMMM-YYYY'
        });
				$('#birth_date').datetimepicker({
          format: 'DD-MMMM-YYYY'
        });
});


</script>
@include('flash')

@stop
