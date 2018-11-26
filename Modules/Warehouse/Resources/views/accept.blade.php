@extends('app')

{{-- page level styles --}}
@section('header_styles')
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
    <!--section starts-->
<h1>{{$gudang->warehouse_name}}<small>&nbsp;<span id="waktu"></span></small></h1>
<ol class="breadcrumb">
    <li><a href="/"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><a href="#">{{$gudang->warehouse_name}}</a></li>
</ol>
</section>

<section class="content">
  <div class="row">
    <div class="col-md-8">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title">Barang & Barang Siap Jual dalam Perjalanan</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
					<form method="post" id="formsatuan">
						<meta id="token" name="token" content="{{ csrf_token() }}">
						<input id="idtab" type="hidden" value="{{$gudang->id}}">
          <table id="sediabarangtable" class="table table-bordered table-hover">
            <thead>
            <tr>
							<th ></th>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Id</th>
							<th style="text-align:center">Kode Order/ No Surat</th>
              <th style="text-align:center">Supplier/ Asal</th>
              <th style="text-align:center">Tgl Order/ Tgl Surat</th>
              <th style="text-align:center">Aksi</th>
            </tr>
            </thead>
            <tbody>
		        </tbody>
          </table>
				</form>
        </div>
            <!-- /.box-body -->
      </div>
	<!-- /.box -->
	  </div>
    <div class="col-md-4">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title" id="judulsatuan">Terima Barang & Barang Siap Jual</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="container-fluid add-product" id="tableform">
							<form id="terimaform" action="" method="post"  enctype="multipart/form-data" data-toggle="validator">
										<input  type="hidden" name="_method" value="PATCH">
										<input  type="hidden" name="gudang" value="{{$gudang->id}}">
										<input  type="hidden" name="lokasi" value="{{$gudang->warehouse_code}}">
										<input id="r" type="hidden" name="r">
										<input id="jenis" type="hidden" name="jenis">
										 {{csrf_field()}}
											<div class="row" id="rowinfo">
												<div class="form-group col-md-6">
													<label id="kodeorder" for="invoice_id" class=" control-label">Kode Order : </label>
													<input id="invoice_id" type="text" class="form-control" name="invoice_id" readonly>
												</div>
												<div class="form-group col-md-6">
													<label id="tanggalorder" for="order_date" class=" control-label">Tanggal Order : </label>
													<input id="order_date" type="text" class="form-control" name="order_date" readonly>
												</div>
											</div>
											<div  id="rowtable">
											<table class="table table-border table-hover" id="infoform">
										 		<thead>
												<th style="text-align:center; width:5px">No</th><th style="text-align:center">Produk</th><th style="text-align:center">Jumlah</th>
												<th style="text-align:center">Satuan</th>
										 		</thead>
												<tbody></tbody>
											</table>
											</div>
											<div class="row" id="rowasal">
												<div class="form-group col-md-12" >
													<div class="form-group">
														<label for="send_date" class="control-label">Asal</label>
														<input id="from" type="text" class="form-control" name="from" readonly>
													</div>
												</div>
											</div>
											<div class="row" id="rowtanggal">
												<div class="form-group col-md-12" >
													<div class="form-group">
														<label for="send_date" class="control-label">Tanggal Terima</label>
														<div class="input-group date">
																<div class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></div>
																<input type="text" class="form-control"  name="send_date" id="datetimepicker1" readonly required/>
														</div>
													</div>
												</div>
											</div>
											<div class="row" id="rowsubmit">
												<div class="form-group col-md-12">
														 <input id="submit" type="submit" class="form-control btn btn-primary prod-submit" value="Terima Barang">
												</div>
											</div>
										</form>
          </div>
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
@section('footer_scripts')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript">
function format ( d ) {
    var trs=''; //just a variable to construct
    var i=0;
    $.each($(d.product),function(key,value){
        i++;
        trs+='<tr><td style="text-align:center">'+i+'</td><td>'+value+
				'<input id="product_id" type="hidden" value='+d.idproduct[key]+'>'+
				'</td><td style="text-align:center">'+d.quantity[key]+'</td><td style="text-align:center">'
        +d.measure[key]+'</td></tr>';
    })
    return '<table class="table table-border table-hover">'+
           '<thead>'+
              '<th style="text-align:center; width:5px">No</th>'+'<th style="text-align:center">Produk</th>'+'<th style="text-align:center">Jumlah</th>'+
              '<th style="text-align:center">Satuan</th>'+
           '</thead><tbody>'
               + trs +
        '</tbody></table>';
}
var idtab = $('#idtab').val();
var table = $('#sediabarangtable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 3,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/sedia/getsedia/"+idtab},
    columns: [
			  {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data:0, width: '7px'},{data: 1, visible: false},{data: 2},{data: 3},
				{data:4, className: 'dt-center'},{data: 5, className: 'dt-center'}
    ],
        order: [1, 'asc'],
});
$('#sediabarangtable tbody').on('click', 'td.details-control', function () {
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
// Showing add product Form
$('#sediabarangtable tbody').on( 'click', '#clickterima', function () {
    $('#infoform').remove();
		var t = table.row($(this).closest('tr')).data();
		var id = table.row($(this).closest('tr')).data()[1];
		var invoiceid = table.row($(this).closest('tr')).data()[2];
		var orderdate = table.row($(this).closest('tr')).data()[4];
		var from = table.row($(this).closest('tr')).data()[3];
		var r = table.row($(this).closest('tr')).data()[6];
		var jenis = table.row($(this).closest('tr')).data()['jenis'][0];
		var trs=''; //just a variable to construct
		var i=0;
		if(r==2){
			$('#kodeorder').text("No Surat");
			$('#tanggalorder').text("Tanggal Surat");
			$('#r').val(r);
		}
		$('#invoice_id').val(invoiceid);
		$('#order_date').val(orderdate);
		$('#from').val(from);
		$('#jenis').val(jenis);

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

		$('#terimaform').attr('action', '/terimabarang/'+id);
		$('#datetimepicker1').removeAttr('readonly');

});

$('#datetimepicker1').datetimepicker({format:'DD-MM-YYYY'});
</script>
@include('flash')
@stop
