@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

{{-- page level styles --}}
@section('css')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
<style>
.box-header {background-color: #F89A14;}
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
</style>
@stop

@section('content')
<section class="content-header">
<h1>&nbsp;{{$warehouse->name}}</h1>
<ol class="breadcrumb" style="font-size:16px">
    <li><b>Total Quantity : {{number_format($inventory->quantity)}}</b></li>
    <li><b>Total Value  : {{number_format($inventory->sub_total,2)}}</b></a></li>
</ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-12">
		<div class="box">
			<div class="box-body">
					<input id="idtab" type="hidden" value="{{$warehouse->hashid}}">
				<table id="inventorytable" class="table table-bordered table-hover" style="width:100%">
					<thead>
						<tr>
							<th style="text-align:center">No</th>
							<th style="text-align:center">Id</th>
							<th style="text-align:center">Code</th>
							<th style="text-align:center">Name</th>
							<th style="text-align:center">Qty</th>
							<th style="text-align:center">Measure</th>
							<th style="text-align:center">Sub_Total</th>
							<th style="text-align:center">Avg Price</th>
							<th style="text-align:center">Action</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
		</div>
	  </div>
@include('warehouse::detail')
 </div>
</section>
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript">
var idtab = $('#idtab').val();
var table = $('#inventorytable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/warehouse/getinventory/"+idtab},
    columns: [
        {data:0, width: '7px'},{data: 1, visible:false},{data: 2},
				{data:3},{data: 4, className: 'dt-right'},{data:5},{data:6, className: 'dt-right'},{data:8, className: 'dt-right'},
				{data:7, className: 'dt-center'}
    ],
        order: [1, 'asc'],
});

function detailForm(id,name){
	$('#modal-form').modal('show');

	var tabledetail=$('#tabledetail').DataTable({
		  destroy: true,
	    processing: true,
	    serverSide: true,
	    pageLength: 5,
	    ajax: {"url" : "/warehouse/"+id+"/show/"+idtab},
	    columns: [
	        {data:0, width: '7px', orderable:false},{data: 1, visible: false},{data: 7},{data: 4, className: 'dt-right'},
					{data:5},{data: 9, className: 'dt-right'},{data: 8},{data: 6}
	    ],
	        order: [1, 'desc'],
	});
	$('#detailtitle').text(name);
}

</script>
@include('flash')
@stop
