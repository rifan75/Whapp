@extends('adminlte::page')

@section('title', 'WApp|Purchase')

{{-- page level styles --}}
@section('css')
<link rel="stylesheet" href="/css/bootstrap-datetimepicker.css">
<style>
  .modal-header {background-color: #F89A14;}
  .modal-title {display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;padding: 0;
    color: #fff}
  .box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	   padding: 0;margin-bottom: 8px;color: #fff}
  .skuform{padding-top:20px;padding-right: 20px;padding-bottom: 20px;padding-left: 20px;border: 2px solid rgba(0, 0, 0, 0.3);}
  html, body {height: 100%;}
  #startfile {margin: 2em 0;}
     /* Mimic table appearance */
  div.table {display: table;}
  div.table .file-row {display: table-row;}
  div.table .file-row > div {display: table-cell;vertical-align: top;border-top: 1px solid #ddd;padding: 8px;}
  div.table .file-row:nth-child(odd) {background: #f9f9f9;}
  .cancel{float: right;}
}


</style>
@stop
@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="panel panel-default">
        <br>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-3">
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
@include('purchase::contentpurchaseedit')
            </div>
          </div>
        </div>
      </div>
    </div>
 </div>
</section>
<!-- /.row -->
@stop
{{-- page level scripts --}}
@section('js')
<script type="text/javascript" src="/js/bootstrap-datetimepicker.js" ></script>

<script type="text/javascript">
$(function () {
        $('#datetimepicker1').datetimepicker({
          format:'DD-MMM-YYYY'
        });

    });
</script>


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
    ajax: {"url" : "/purchase/getproduct"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, visible: false},
        {data: 3, orderable: false},
        {data: 9, orderable: false},
    ],
        order: [0, 'desc'],
});

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

$('#producttable tbody').on( 'click', '#clicktabel', function () {
    $('#trpajangan').remove();
    var code = table.row($(this).closest('tr')).data()[2];
    var name = table.row($(this).closest('tr')).data()[3];
    var id = table.row($(this).closest('tr')).data()[1];
    var measure = table.row($(this).closest('tr')).data()[12];
    $('#addBarang').append('<tr id="inputtable'+id+'" class="item"><td>'+code+'</td><td>'+name+
    '</td><td><input type="text" class="hitung form-control" min="1"  style="text-align:right; width:60px" autocomplete="off" name="quantity[]" size="1"/></td>'+
    '<td><input id="measure" class="form-control" type="text" name="measure[]" size="1" value='+measure+' readonly/></td>'+
    '<td><input type="text" class="hitung form-control" min="1" style="text-align:right" autocomplete="off" name="price[]" size="4"/></td>'+
    '<td><input id="sub_total" type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="sub_total[]" size="4" readonly/></td>'+
    '<input id="product_id" type="hidden" name="product_id[]" value='+id+'>'+
    '<td><button class="btn btn-danger btn-xs" type="button" onclick="removeinput(\''+id+'\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
} );

function removeinput(id){
   $('#inputtable'+id+'').remove();
   calcTotals();
}

function generatepur(){
  $.ajax({
    url : "/purchase/gen",
    type : "GET",
    dataType : "JSON",
    success : function(data){
      $('#invoice_id').val(data);
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}

$(document).on("click change paste keyup", ".hitung", function() {
       calcTotals();
   });

function calcTotals(){
       var total = 0;
       var totalItem = 0;
       var subtotal    = 0;
       $('tr.item').each(function(){
           var quantity    = parseFloat($(this).find("[name='quantity[]']").val());
           var price        = parseFloat($(this).find("[name='price[]']").val());
           subtotal   = parseFloat(quantity * price) > 0 ? parseFloat(quantity * price) : 0;
           totalItem        += parseFloat(price * quantity) > 0 ? parseFloat(price * quantity) : 0;
           $(this).find("[name='sub_total[]']").val( subtotal.toLocaleString('en', {minimumFractionDigits: 2}) );
       });
       total           += parseFloat(totalItem);
       $( '#total' ).val( total.toLocaleString('en', {minimumFractionDigits: 2})  );
}

</script>

@include('flash')

@endsection
