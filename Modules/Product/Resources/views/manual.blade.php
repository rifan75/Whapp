@extends('adminlte::page')

@section('title', 'WApp|Product')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
<style>
.box-header {background-color: #F89A14;}
.box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	          padding: 0;margin-bottom: 8px;color: #fff
          }
.col-md-3, .col-md-9 {padding-left: 0px}
</style>
@stop

@section('content')
<section class="content">

  <div class="row">
		<div class="col-md-3">
			<div class="box">
				<div class="box-body">
						<table id="producttable" class="table table-hover">
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
			</div>
	 </div>
    <div class="col-md-9">
		<div class="box">
			<div class="box-body">
				<form method="post" id="formmanual">
					<meta id="token" name="token" content="{{ csrf_token() }}">
				<table id="inventorytable" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th ></th>
						<th style="text-align:center">No</th>
						<th style="text-align:center">Id</th>
						<th style="text-align:center">code</th>
						<th style="text-align:center">Name</th>
						<th style="text-align:center">Qty</th>
						<th style="text-align:center">Measure</th>
						<th style="text-align:center">Sub Total</th>
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


</div>

@include('product::inputmanual')
  <!-- /.row -->

</section>
    <!-- /.content -->
@stop

{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" ></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>@{{2}} - @{{4}} - @{{5}} - @{{6}}</td></tr>
        <tr><td><image src="@{{11}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var table = $('#producttable').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false,
    pageLength: 10,
    pagingType: "simple",
    ajax: {"url" : "/product/getunsettleproduct"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, visible: false},
        {data: 3, orderable: false},
        {data: 9, orderable: false},
    ],
        order: [1, 'desc'],
});
// Add event listener for opening and closing details
$('#producttable tbody').on('click', 'td.details-control', function () {
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
</script>
<script id="detailsinventory-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>Location</td><td>@{{6}}</td></tr>
        <tr><td>type</td><td>@{{9}}</td></tr>
				<tr><td>Note</td><td>@{{10}}</td></tr>
    </table>
</script>
<script type="text/javascript">
var template1 = Handlebars.compile($("#detailsinventory-template").html());
var tableinventory = $('#inventorytable').DataTable({
    processing: true,
    pageLength: 10,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/product/getinventorymanual"},
    columns: [
				{"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data:0, width: '7px'},{data: 1, visible: false , orderable: false},{data: 2},{data: 3},
				{data:4, className: 'dt-center'},{data:5, className: 'dt-center'},{data:8, className: 'dt-right'},{data: 7, className: 'dt-center'}
    ],
        order: [1, 'asc'],
});

$('#inventorytable tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = tableinventory.row( tr );
    if ( row.child.isShown() ) {
        // This row is already open - close it
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        // Open this row
        row.child( template1(row.data()) ).show();
        tr.addClass('shown');
    }
});

$('#producttable tbody').on( 'click', '#clicktabel', function () {
    $('#modalinput-form').modal('show');
		var id = table.row($(this).closest('tr')).data()[1];
		var name = table.row($(this).closest('tr')).data()[3];
		var code = table.row($(this).closest('tr')).data()[2];
		var measure = table.row($(this).closest('tr')).data()[12];

    $('#id_product').val(id);
		$('#codetext').text(code);
		$('#nametext').text(name);
    $('#measure').val(measure);

});

function editForm(id){
		$('#forminputmanual')[0].reset();
    $('#inputhidden').val('PATCH');
		$('#title').text('Edit Manual Input');
		$('#modalinput-form').modal('show');
    $.ajax({
      url : "/product/manual/"+id+"/edit",
      type : "GET",
      dataType : "JSON",
      success : function(data){
				var price = data.sub_total/data.in_out_qty;
				$('#id_product').val(data.id_product);
				$('#codetext').text(data.code);
				$('#nametext').text(data.name);
				$('#measure').val(data.measure);
				$('#in_out_qty').val(data.in_out_qty);
		    $('#sub_total').val(data.sub_total.toLocaleString('en', {minimumFractionDigits: 2}));
				$('#price').val(price.toLocaleString('en', {minimumFractionDigits: 2}));
				$('#type').val(data.type);
				$('#warehouse').val(data.type);
				$('#note').val(data.note);
        $('#forminputmanual').attr('action', '/product/manualedit/'+id);
      },
      error : function() {
        swal("Error","Cannot Show Record !","error");
      },
    });
  }

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
              url : "/product/manual/"+id,
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

$(document).on("click change paste keyup", ".hitung", function() {
       calcTotals();
});

function calcTotals(){
       var subtotal    = 0;
       var quantity = parseFloat($('#in_out_qty').val());
       var price = parseFloat($('#price').val());
       subtotal = parseFloat(quantity * price);
       $('#sub_total').val( subtotal.toLocaleString('en', {minimumFractionDigits: 2}) );
}
</script>
@include('flash')
@stop
