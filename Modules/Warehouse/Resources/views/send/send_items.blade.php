@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

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
                    <table id="add_send_table" class="table table-hover">
                      <input id="idtab" type="hidden" value="{{$location->hashid}}">
                      <input id="idlok" type="hidden" value="{{$location->code}}">
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
@include('warehouse::send.send_content')
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
        $('#datetimepicker').datetimepicker({
          format:'DD-MMM-YYYY'
        });

    });
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>@{{2}} - @{{10}} - @{{11}} - @{{12}}</td></tr>
        <tr><td><image src="@{{9}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var idtab = $('#idtab').val();
var table = $('#add_send_table').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false,
    pageLength: 10,
    pagingType: "simple",
    ajax: {"url" : "/warehouse/sendinventory/"+idtab},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, visible: false},
        {data: 3, orderable: false},
        {data: 8, orderable: false},
    ],
        order: [0, 'desc'],
});

$('#add_send_table tbody').on('click', 'td.details-control', function () {
    var tr = $(this).closest('tr');
    var row = table.row( tr );
    if ( row.child.isShown() ) {
        row.child.hide();
        tr.removeClass('shown');
    }
    else {
        row.child( template(row.data()) ).show();
        tr.addClass('shown');
    }
});

$('#add_send_table tbody').on( 'click', '#clicktabel', function () {
    $('#trpajangan').remove();
    var code = table.row($(this).closest('tr')).data()[2];
    var name = table.row($(this).closest('tr')).data()[3];
    var product_id = table.row($(this).closest('tr')).data()[1];
    var measure = table.row($(this).closest('tr')).data()[6];
    var maksimum = table.row($(this).closest('tr')).data()[5];
    var price = table.row($(this).closest('tr')).data()[4];
    if($('#inputtable'+product_id).length){
      $('#inputtable'+product_id).remove();
    }
    $('#addBarang').append('<tr id="inputtable'+product_id+'" class="item"><td>'+code+'</td><td>'+name+
    '</td><td><input id="quantity" type="text" class="hitung form-control" min="1"  style="text-align:right; width:60px" autocomplete="off" name="quantity[]" size="1"/></td>'+
    '<td><input id="measure" class="form-control" type="text" name="measure[]" size="1" value='+measure+' readonly/></td>'+
    '<td><input id="maksimum" type="text" class="hitung form-control" style="text-align:right" autocomplete="off" name="maksimum[]" size="4" value='+maksimum+' readonly/></td>'+
    '<input id="product_id"  type="hidden" name="product_id[]" value='+product_id+'>'+
    '<input id="hargabeli" type="hidden" name="price[]" value='+price+'>'+
    '<input id="sub_total" type="hidden" name="sub_total[]">'+
    '<input id="sisa" type="hidden" name="sisa[]">'+
    '<td><button class="btn btn-danger btn-xs" type="button" onclick="removeinput(\''+product_id+'\')"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>');
} );

function removeinput(id){
   $('#inputtable'+id+'').remove();
   calcTotals();
}

$(document).on("click change paste keyup", ".hitung", function() {
       calcTotals();
   });

function calcTotals(){
   $('tr.item').each(function(){
       var quantity = parseFloat($(this).find("[name='quantity[]']").val());
       var maksimum = $(this).find("[name='maksimum[]']").val();
       var price = $(this).find("[name='price[]']").val();
       var subtotal   = parseFloat(quantity * price) > 0 ? Math.round(parseFloat(quantity * price)) : 0;
       $(this).find("[name='sub_total[]']").val( subtotal.toLocaleString('en', {minimumFractionDigits: 2}) );
       var sisa = maksimum - quantity;
       $(this).find("[name='sisa[]']").val( sisa.toLocaleString('en', {minimumFractionDigits: 2}) );
       if($(this).find("[name='sisa[]']").val() < 0) {
          $(this).find("[name='quantity[]']").css({"border-color": "#ff0000","border-width": "3px"}) ;
          $("button[type=submit]").addClass('disabled');
          return false;
        }else{
          $("input[name='quantity[]']").css({"border-color": "#d2d6de","border-width": "1px"}) ;
        }

   });

}
function generatekir(){
  var from = $('#idlok').val();
  $.ajax({
    url : "/warehouse/sendgen/"+from,
    type : "GET",
    dataType : "JSON",
    success : function(data){
      $('#no_letter').val(data);
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}
</script>

@include('flash')

@endsection
