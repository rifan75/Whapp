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
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Delivery on The Way</h3>
        </div>
        <div class="box-body">
					<form method="post" id="formsatuan">
						<meta id="token" name="token" content="{{ csrf_token() }}">
						<input id="idtab" type="hidden" value="{{$warehouse->hashid}}">
          <table id="accepttable" class="table table-bordered table-hover">
            <thead>
            <tr>
							<th ></th>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Id</th>
							<th style="text-align:center">Order Code/ Letter No</th>
              <th style="text-align:center">Supplier/ From</th>
              <th style="text-align:center">Order/ Letter Date</th>
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
          <h3 class="box-title" id="judulsatuan">Accepting Items</h3>
        </div>
        <div class="box-body">
          <div class="container-fluid add-product" id="tableform">
							<form id="acceptform" action="" method="post"  enctype="multipart/form-data" data-toggle="validator">
										<input  type="hidden" name="_method" value="PATCH">
										<input  type="hidden" name="warehouse" value="{{$warehouse->hashid}}">
										<input  type="hidden" name="lokasi" value="{{$warehouse->code}}">
										<input id="r" type="hidden" name="r">
										 {{csrf_field()}}
											<div class="row" id="rowinfo">
												<div class="form-group col-md-6">
													<label id="kodeorder" for="invoice_id" class=" control-label">Order Code : </label>
													<input id="invoice_id" type="text" class="form-control" name="invoice_id" readonly>
												</div>
												<div class="form-group col-md-6">
													<label id="tanggalorder" for="order_date" class=" control-label">Order Date : </label>
													<input id="order_date" type="text" class="form-control" name="order_date" readonly>
												</div>
											</div>
											<div  id="rowtable">
											<table class="table table-border table-hover" id="infoform">
										 		<thead>
												<th style="text-align:center; width:5px">No</th><th style="text-align:center">Product</th><th style="text-align:center">Qty</th>
												<th style="text-align:center">Measure</th>
										 		</thead>
												<tbody></tbody>
											</table>
											</div>
											<div class="row" id="rowasal">
												<div class="form-group col-md-12" >
													<div class="form-group">
														<label for="from" class="control-label">From</label>
														<input id="from" type="text" class="form-control" name="from" readonly>
													</div>
												</div>
											</div>
											<div class="row" id="rowtanggal">
												<div class="form-group col-md-12" >
													<div class="form-group">
														<label for="accepting_date" class="control-label">Accepting Date</label>
														<div class="input-group date">
																<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
																<input type="text" class="form-control"  name="send_date" id="datetimepicker1" readonly required/>
														</div>
													</div>
												</div>
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
 </div>
</section>
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript">
function format ( d ) {
    var trs='';
    var i=0;
    $.each($(d.product),function(key,value){
        i++;
        trs+='<tr><td style="text-align:center">'+i+'</td><td>'+value+
				'<input id="product_id" type="hidden" value='+d.idproduct[key]+'>'+
				'</td><td style="text-align:right">'+d.quantity[key]+'</td><td style="text-align:left">'
        +d.measure[key]+'</td></tr>';
    })
    return '<table class="table table-border table-hover">'+
           '<thead>'+
              '<th style="text-align:center; width:5px">No</th>'+'<th style="text-align:center">Product</th>'+'<th style="text-align:center">Qty</th>'+
              '<th style="text-align:center">Measure</th>'+
           '</thead><tbody>'
               + trs +
        '</tbody></table>';
}
var idtab = $('#idtab').val();
var table = $('#accepttable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 5,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/warehouse/"+idtab+"/delivery"},
    columns: [
			  {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data:0, orderable: false},{data: 1, visible: false},{data: 2},{data: 3},
				{data:4, className: 'dt-center'},{data: 5, className: 'dt-center'}
    ],
        order: [0, 'asc'],
});
$('#accepttable tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );

        if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
        }
        else {
          // Open this row
          row.child( format(row.data()) ).show();
          tr.addClass('shown');
        }
    });

$('#accepttable tbody').on( 'click', '#clickterima', function () {
    $('#infoform').remove();
		var t = table.row($(this).closest('tr')).data();
		var id = table.row($(this).closest('tr')).data()[1];
		var invoiceid = table.row($(this).closest('tr')).data()[2];
		var orderdate = table.row($(this).closest('tr')).data()[4];
		var from = table.row($(this).closest('tr')).data()[3];
		var r = table.row($(this).closest('tr')).data()[6];
		var trs='';
		var i=0;
		if(r==2){
			$('#kodeorder').text("Letter No");
			$('#tanggalorder').text("Letter Date");
			$('#r').val(r);
		}else{
			$('#kodeorder').text("Order Code");
			$('#tanggalorder').text("Order Date");
			$('#r').val(r);
		}
		$('#invoice_id').val(invoiceid);
		$('#order_date').val(orderdate);
		$('#from').val(from);

		$.each($(t.product),function(key,value){
				i++;
				trs+='<tr><td style="text-align:center">'+i+'</td><td>'+value+'</td>'+
				'<input id="product_id" type="hidden" name="product_id[]" value='+t.idproduct[key]+'>'+
				'<td style="text-align:center">'+t.quantity[key]+'</td>'+
				'<input id="product_id" type="hidden" name="quantity[]" value='+t.quantity[key]+'>'+
				'<td style="text-align:center">'+t.measure[key]+'</td>'+
				'<input id="product_id" type="hidden" name="measure[]" value='+t.measure[key]+'>'+
				'<input id="product_id" type="hidden" name="sub_total[]" value='+t.sub_total[key]+'>'+
				'</tr>';
		});
		$('#rowtable').
		append('<table class="table table-border table-hover" id="infoform">'+
					 		'<thead>'+
							'<th style="text-align:center; width:5px">No</th>'+'<th style="text-align:center">Produk</th>'+'<th style="text-align:center">Jumlah</th>'+
							'<th style="text-align:center">Satuan</th>'+
					 		'</thead>'+
							'<tbody>'+ trs +'</tbody>'+
						'</table>');

		$('#acceptform').attr('action', '/warehouse/accept/'+id);
		$('#datetimepicker1').removeAttr('readonly');

});

$('#datetimepicker1').datetimepicker({format:'DD-MMM-YYYY'});
</script>
@include('flash')
@stop
