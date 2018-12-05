@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

{{-- page level styles --}}
@section('css')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
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
		<div class="col-md-4">
			<div class="box">
				<div class="box-header">
					<h3 class="box-title" id="judulsatuan">Input Stock Opname</h3>
				</div>
				<div class="box-body">
					<div class="container-fluid add-product" id="tableform">
							<form id="terimaform" action="/opname" method="post"  enctype="multipart/form-data" data-toggle="validator">
										 {{csrf_field()}}
												<div class="form-group">
													<label id="kodeorder" for="opname_id" class=" control-label">Stock Opname Code : </label>
													<input id="no_stockopname" type="text" class="form-control" name="no_stockopname" readonly>
												</div>
												<div class="form-group">
													<label for="send_date" class="control-label">Stock Opname Date</label>
													<div class="input-group date">
															<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
															<input type="text" class="form-control"  name="stockopname_date" id="datetimepicker1"  required/>
													</div>
												</div>
												<div class="form-group">
													<label for="location" class="control-label">Location</label>
														<select class="form-control" name="location" id="location">
															@foreach($warehouses as $warehouse)
																<option value="{{ $warehouse->code }}">{{ $warehouse->name }}</option>
															@endforeach
														</select>
												</div>
												<div class="form-group">
														<label for="note">Note : </label>
														<textarea name="note" id="note" cols="30" rows="1" class="form-control"></textarea>
												</div>
											<div class="row" id="rowsubmit">
												<div class="form-group col-md-12">
														 <input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Submit">
												</div>
											</div>
										</form>
					</div>
				</div>
			</div>
	 </div>
    <div class="col-md-8">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title">Stock Opname List</h3>
			</div>
			<div class="box-body">
				<table id="sediabarangtable1" class="table table-bordered table-hover" style="width:100%">
					<thead>
					<tr>
						<th style="text-align:center">No</th>
						<th style="text-align:center">Id</th>
						<th style="text-align:center">Code</th>
						<th style="text-align:center">Date</th>
						<th style="text-align:center">Location</th>
						<th style="text-align:center">Note</th>
						<th style="text-align:center">Process</th>
					</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
					<!-- /.box-body -->
		</div>
	<!-- /.box -->
	  </div>

  <!-- /.row -->
 </div>
</section>
    <!-- /.content -->
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript">
var opname_id = moment().format('DD')+moment().format('MM')+moment().format('YYYY')+moment().format('HH')+moment().format('mm')+moment().format('ss');
$('#no_stockopname').val("SO-"+opname_id);
var table1 = $('#sediabarangtable1').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/stockopname/getopname"},
    columns: [
        {data:0, width: '7px'},{data: 1, visible: false},{data: 2},{data: 5},
				{data:3},{data: 6},{data:7, className: 'dt-center'}
    ],
        order: [1, 'asc'],
});

$('#datetimepicker1').datetimepicker({format:'DD-MMM-YYYY'});

$("#imageinvoice_path").change(function(){
		readURL(this);
});

$(document).on("click change paste keyup", ".hitung", function() {
       calcTotals();
});

function calcTotals(){
       var subtotal    = 0;
       var quantity = parseFloat($('#in_out_qty').val());
       var price = parseFloat($('#sediaprice').val());
       subtotal = parseFloat(quantity * price);
       $('#sub_total').val( subtotal.toLocaleString() );
}
</script>
@include('flash')
@stop
