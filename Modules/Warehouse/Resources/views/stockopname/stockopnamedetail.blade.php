@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

{{-- page level styles --}}
@section('css')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
<style>
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
</style>
@stop

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
				<div class="box-header">
          <a onclick="finishForm('{{$inventory->opname}}')" class="btn btn-success"><i class="fa fa-plus-circle"></i>&nbsp;Finishing Stock Opname</a>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
					<form method="post" id="formsatuan">
						<meta id="token" name="token" content="{{ csrf_token() }}">
						<input id="idtab" type="hidden" value="{{$inventory->opname}}">
						<input id="idwh" type="hidden" value="{{$inventory->hashwh}}">
          <table id="opnametable" class="table table-bordered table-hover" style="width:100%">
            <thead>
            <tr>
							<th ></th>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Opname Id</th>
							<th style="text-align:center">Code</th>
              <th style="text-align:center">Name</th>
              <th style="text-align:center">Stock Recorded</th>
							<th style="text-align:center">Stock After Checked</th>
              <th style="text-align:center">Measure</th>
							<th style="text-align:center">Action</th>
            </tr>
            </thead>
            <tbody>
		        </tbody>
          </table>
				</form>
        </div>
            <!-- /.box-body -->
      </div>
	  </div>

  <!-- /.row -->
 </div>
@include('warehouse::stockopname.detailopname')
@include('warehouse::stockopname.opnameconf')
</section>
    <!-- /.content -->
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>
<script type="text/javascript">
function format ( d ) {
    return '<table class="table table-border table-hover">'+
					 '<tbody>'+
               '<tr><td><image src='+d.picture+' width="200px"></td></tr>'+
           '</tbody></table>';
}
var idtab = $('#idtab').val();
var idwh = $('#idwh').val();
var table = $('#opnametable').DataTable({
    processing: true,
    serverSide: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/stockopname/getopnamedetail/"+idtab},
    columns: [
			  {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data:0, width: '7px'},{data: 1, visible: false},{data: 2},{data: 3},
				{data: 6, className: 'dt-right'}, {data: 5, className: 'dt-center'}, {data:4},
				{data:7, className: 'dt-center'}
    ],
        order: [1, 'asc'],
});
$('#opnametable tbody').on('click', 'td.details-control', function () {
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


$('#opnametable tbody').on( 'click', '#clicksave', function () {
    $('#opnameconf-form').modal('show');
		var t =  table.row($(this).closest('tr')).data();
		var d =  $(this).closest('tr').find("input").val();
		var code = table.row($(this).closest('tr')).data()[2];
		var name = table.row($(this).closest('tr')).data()[3];
		var measure = table.row($(this).closest('tr')).data()[4];
		var recorded = table.row($(this).closest('tr')).data()[6];
		var stock = table.row($(this).closest('tr')).data()['stock'];
		var noopname = table.row($(this).closest('tr')).data()[8];
		var product = table.row($(this).closest('tr')).data()['product'];
		var price = table.row($(this).closest('tr')).data()['price'].replace(",", "");
		$('#codetext').text(code);
		$('#nametext').text(name);
		$('#measure').val(measure);
		$('#measure1').val(measure);
		$('#recorded').val(recorded);
		$('#stock').val(stock);
		$('#noopname').val(noopname);
		$('#id_product').val(product);
		$('#price').val(price);
		$('#opname1').val(d);
		var sub_total = parseFloat(d * price);
		$('#subtotal').val( sub_total.toLocaleString('en', {minimumFractionDigits: 2}) );
});

function showForm(id,name){
	$('#modal-form').modal('show');

	var tabelsedia=$('#tabledetail').DataTable({
		  destroy: true,
	    processing: true,
	    serverSide: true,
	    pageLength: 5,
	    ajax: {"url" : "/stockopname/"+id+"/show/"+idwh},
	    columns: [
	        {data:0, width: '7px'},{data: 1, visible: false},{data: 7},{data: 4},
					{data:5},{data: 6}
	    ],
	        order: [1, 'desc'],
	});
	$('#title').text(name);
}


	function finishForm(id) {
    swal({
			title: 'Are You Sure, Its Finish ?',
      text: "Data will be recap as stock opname No : "+id,
      type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Ya, Finish!'
		}).then((result) => {
			if (result.value) {
						$.ajax({
							url : "/opnamefinish/"+id,
							type : "POST",
							beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
							success : function(data){
              location.href = "/stockopname";
              swal("Sukses","Stok Opname is Finished","success");
						},
							error : function(data) {
							swal("Error","Cannot Process !","error");
						}
    				});
			}
	});
}

</script>
@include('flash')
@stop
