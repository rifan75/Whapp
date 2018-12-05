@extends('adminlte::page')

@section('title', 'WApp|Delete')

@section('css')
<style>
.box-header {background-color: #F89A14;}
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
.col-md-3, .col-md-9 {padding-left: 0px}
tr:nth-child(odd) td {
   background: #FFFFFF;
}
tr:nth-child(even) td {
   background: #d3d3d3;
}
th {background: grey; }
</style>
@stop

@section('content')
<section class="content">

  <div class="row">
    <div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<form method="post" id="form">
					<meta id="token" name="token" content="{{ csrf_token() }}">
				<table id="inventorytable" class="table table-bordered table-hover">
					<input  id="model" type="hidden" name='model' value="Inventory">
					<thead>
					<tr>
						<th style="text-align:center">Id</th>
						<th style="text-align:center">Product Code</th>
						<th style="text-align:center">Product Name</th>
						<th style="text-align:center">Warehouse</th>
						<th style="text-align:center">Action</th>
					</tr>
					</thead>
					<tbody>
						@foreach($datas as $data)
            <tr>
               <td style="text-align:center">{{ $data->id }}</td>
               <td>{{ $data->product->code }}</td>
							 <td>{{ $data->product->name }}</td>
							 <td>{{ $data->warehouse_data->name }}</td>
							 <td style="text-align:center">
								 <a href="#" onclick="restore_data({{ $data->id }})" class="btn btn-success"role="button">Restore</a>
								 <a href="#" onclick="delete_data({{ $data->id }})" class="btn btn-warning"role="button">Delete Permanently</a>
							 </td>
            </tr>
            @endforeach
					</tbody>
				</table>
				{{ $datas->links() }}
				</form>
			</div>
		</div>
</div>


</div>

  <!-- /.row -->

</section>
    <!-- /.content -->
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript">

function restore_data(id){
  var model = $('#model').val();
  $.ajax({
    url : "/delete/"+id+"/restore/"+model,
    type : "POST",
		data: {_method: 'POST'},
		beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
    success : function(data){
      swal("Success","Record Restored","success");
			location.reload();
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}

function delete_data(id){
  var model = $('#model').val();
  $.ajax({
    url : "/delete/"+id+"/"+model,
    type : "POST",
		data: {_method: 'DELETE'},
		beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
    success : function(data){
      swal("Success","Record Deleted","success");
			location.reload();
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}

</script>
@include('flash')
@stop
