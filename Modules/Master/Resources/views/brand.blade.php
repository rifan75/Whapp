@extends('adminlte::page')

@section('title', 'WApp|Brand')

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
          <h3 class="box-title">Brand List</h3>
        </div>
        <div class="box-body">
          <form method="post" id="formbrand">
						<meta id="token" name="token" content="{{ csrf_token() }}">
          <table id="brandtable" class="table table-bordered table-hover">
            <thead>
            <tr>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Id</th>
              <th style="text-align:center">Name</th>
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
          <h3 id="brandtitle" class="box-title">Input Brand</h3>
        </div>
        <div class="box-body">
            <div class="container-fluid">
            <form id="brandform" action="{{ url('master/brand') }}" method="post" data-toggle="validator">
              @csrf
							<input id="inputhidden" type='hidden' name='_method' value='POST'>
                <div class="row">
	                <div class="form-group col-md-12">
	                	<label for="name" class=" control-label">Name : </label>
	                	<input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
	                  <p style="color:red">{{ $errors->first('name') }}</p>
	                </div>
                </div>
                <div class="row">
	                <div class="form-group col-md-12">
	            		<input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Add Brand">
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
var table = $('#brandtable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/master/getbrand"},
    columns: [
        {data: 0, width: '10px', orderable: false},{data: 1,  visible: false},{data: 2},{data: 3},
				{data: 4, className: 'dt-center', orderable: false}
    ],
        order: [0, 'desc'],
});
// Showing add product Form
function editForm(id){
    $('#inputhidden').val('PATCH');
    $('#brandform')[0].reset();
    $.ajax({
      url : "/master/brand/"+id+"/edit",
      type : "GET",
      dataType : "JSON",
      success : function(data){
        $('#brandtitle').text('Edit Brand');
        $('#submit').val('Edit Brand');
        $('#name').val(data.name);
        $('#brandform').attr('action', 'brand/'+id);
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
              url : "/master/brand/"+id,
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
