@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

{{-- page level styles --}}
@section('css')
<style>
.box-header {background-color: #F89A14;}
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
</style>
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">warehouse List</h3>
        </div>
        <div class="box-body">
          <form method="post" id="formwarehouse">
						<meta id="token" name="token" content="{{ csrf_token() }}">
          <table id="warehousetable" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Id</th>
              <th style="text-align:center">Name</th>
							<th style="text-align:center">Data</th>
							<th style="text-align:center">Note</th>
              <th style="text-align:center">Recorder</th>
              <th style="text-align:center">Action</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
        </form>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 id="warehousetitle" class="box-title">Input Warehouse</h3>
        </div>
        <div class="box-body">
            <div class="container-fluid">
            <form id="warehouseform" action="{{ url('master/warehouse') }}" method="post" data-toggle="validator">
              @csrf
							<input id="inputhidden" type='hidden' name='_method' value='POST'>
                <div class="row">
	                <div class="form-group col-md-12">
	                	<label for="name" class=" control-label">Name : </label>
	                	<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
	                  <p style="color:red">{{ $errors->first('name') }}</p>
	                </div>
									<div class="form-group col-md-12">
	              	<label for="address" class=" control-label">Address : </label>
	              	<textarea id="address"  cols="30" rows="2" class="form-control" name="address">{{ old('address') }}</textarea>
	                <p style="color:red">{{ $errors->first('address') }}</p>
		              </div>
									<div class="form-group col-md-12">
										<label for="city">City</label>
										<input id="city" type="text" class="form-control" name="city" value="{{ old('city') }}" required>
	                  <p style="color:red">{{ $errors->first('city') }}</p>
									</div>
									<div class="form-group col-md-12">
										<label for="state">State</label>
										<input id="state" type="text" class="form-control" name="state" value="{{ old('state') }}" required>
	                  <p style="color:red">{{ $errors->first('state') }}</p>
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
										<label for="pos_code">Pos Code</label>
										<input id="pos_code" type="text" class="form-control" name="pos_code" value="{{ old('pos_code') }}">
									</div>
									<div class="form-group col-md-12">
		              	<label for="phone" class=" control-label">Phone : </label>
		              	<input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}">
		                <p style="color:red">{{ $errors->first('phone') }}</p>
		              </div>
									<div class="form-group col-md-12">
		              	<label for="email" class=" control-label">Email : </label>
		              	<input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}">
		                <p style="color:red">{{ $errors->first('email') }}</p>
		              </div>
									<div class="form-group col-md-12">
		              	<label for="incharge" class=" control-label">Incharge : </label>
		              	<input id="incharge" type="text" class="form-control" name="incharge" value="{{ old('incharge') }}">
		                <p style="color:red">{{ $errors->first('incharge') }}</p>
		              </div>
									<div class="form-group col-md-12">
		              	<label for="note" class=" control-label">Note : </label>
		              	<textarea id="note"  cols="30" rows="2" class="form-control" name="note">{{ old('note') }}</textarea>
		                <p style="color:red">{{ $errors->first('note') }}</p>
		              </div>
                </div>
                <div class="row">
	                <div class="form-group col-md-12">
	            		<input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Add Warehouse">
	                </div>
                </div>
            </form>
            </div>
        </div>
      </div>
  </div>
</section>

@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript">
var table = $('#warehousetable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/master/getwarehouse"},
    columns: [
        {data: 0, width: '10px', orderable: false},{data: 1,  visible: false},{data: 2},{data: 3},{data: 5},{data: 4},
				{data: 6, className: 'dt-center', orderable: false}
    ],
        order: [0, 'desc'],
});
// Showing add product Form
function editForm(id){
    $('#inputhidden').val('PATCH');
    $('#warehouseform')[0].reset();
    $.ajax({
      url : "/master/warehouse/"+id+"/edit",
      type : "GET",
      dataType : "JSON",
      success : function(data){
        $('#warehousetitle').text('Edit Warehouse');
        $('#submit').val('Edit warehouse');
        $('#name').val(data.name);
				$('#address').val(data.address);
				$('#city').val(data.city);
				$('#state').val(data.state);
				$('#country').val(data.country);
				$('#pos_code').val(data.pos_code);
				$('#phone').val(data.phone);
				$('#email').val(data.email);
				$('#contact_person').val(data.contact_person);
				$('#note').val(data.note);
        $('#warehouseform').attr('action', 'Warehouse/'+id);
      },
      error : function() {
        swal("Error","Cannot Show Record !","error");
      },
    });
  }
//Menghapud data
  function deleteForm(id) {
    swal({
      title: 'Are, You Sure ?',
      text: "Record will be deleted permanently",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
			if (result.value) {
            $.ajax({
              url : "/master/warehouse/"+id,
              type : "POST",
              data: {_method: 'DELETE'},
              beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
              success : function(data){
              table.ajax.reload();
              swal("Success","Record deleted","success");
            },
              error : function(data) {
              swal("Error","Cannot Delete Record !","error");
            }
            });
					}
      });
}
</script>
@include('flash')
@stop
