@extends('adminlte::page')

@section('title', 'WApp|Purchase')

{{-- page level styles --}}
@section('css')
<style>
  .modal-header {background-color: #F89A14;}
  .modal-title {display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;padding: 0;
    color: #fff
    }
  .box-title {float: left;display: inline-block;font-size: 18px;line-height: 18px;font-weight: 400;margin: 0;
	   padding: 0;margin-bottom: 8px;color: #fff
    }
</style>
@stop

@section('content')

@if (count($errors) > 0)
<ul>
@foreach ($errors->all() as $error)
<li style="color:red">{{ $error }}</li>
@endforeach
</ul>
@endif

<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <a href="/purchase/order" class="btn btn-success"><i class="fa fa-plus-circle"></i>&nbsp;Add Purchase</a>
        </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form method="post" id="formpurchasetable">
          <meta id="token" name="token" content="{{ csrf_token() }}">
        <table id="purchasetable" class="table table-bordered table-hover" style="width:100%">
          <thead style="vertical-align:middle">
            <tr bgcolor="#D3D3D3" >
              <th ></th>
              <th>No</th>
              <th>Id</th>
              <th style="text-align:center">Inv Code</th>
              <th style="text-align:center">Order Date</th>
              <th style="text-align:center">Send Date</th>
              <th style="text-align:center">Send To</th>
              <th style="text-align:center">Bill (Rp)</th>
              <th style="text-align:center">Supplier</th>
              <th style="text-align:center">Recorder</th>
              <th style="text-align:center">Action</th>
            </tr>
          </thead>
          <tbody></tbody>
         </table>
         </form>
        </div>
        <!-- /.box-body -->
      </div>
<!-- /.box -->
   </div>
 </div>
</section>
<!-- /.row -->

@stop

{{-- page level scripts --}}
@section('js')
<script>
    $('#select-all').click(function(){
      $('input[type="checkbox"]').prop('checked',this.checked);
    });
</script>

<script type="text/javascript">

function format ( d ) {
    var trs=''; //just a variable to construct
    var i=0;
    $.each($(d.product),function(key,value){
        i++;
        trs+='<tr><td style="text-align:center">'+i+'</td><td>'+value+'</td><td style="text-align:center">'+d.quantity[key]+'</td><td style="text-align:center">'
        +d.measure[key]+'</td><td style="text-align:right"></td><td style="text-align:right">'+d.price[key]+
        '</td><td style="text-align:right"></td><td style="text-align:right">'+d.subtotal[key]+'</td></tr>';
        //loop through each product and append it to trs and am hoping that number of price
        //values in array will be equal to number of products
    })
    // `d` is the original data object for the row
    return '<table class="table table-border table-hover">'+
           '<thead>'+
              '<th style="text-align:center; width:5px">No</th>'+'<th style="text-align:center">Product</th>'+'<th style="text-align:center">Qty</th>'+
              '<th style="text-align:center">Measure</th>'+'<th style="text-align:center"></th>'+
              '<th style="text-align:center">Price</th>'+'<th style="text-align:center"></th>'+
              '<th style="text-align:center">Subtotal</th>'+
           '</thead><tbody>'
               + trs +
          '<td></td>'+'<td>Total Purchase</td>'+'<td colspan="6" style="text-align:right"><b>'+d.total+'</b></td>'+
        '</tbody></table>'+
        '<table class="table table-border table-hover">'+
               '<thead>'+
               '<th style="text-align:left; width:5px">Note :</th>'+
               '</thead><tbody>'+
               '<tr><td style="text-align:left">'+d.note+'</td><tr>'+
        '</tbody></table>';
}

var table = $('#purchasetable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/purchase/getpurchase"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, orderable: false},{data: 1,  visible: false},{data: 2},{data: 4, className: 'dt-center'},
        {data: 6, className: 'dt-center'},
        {data: 9},{data: 8, className: 'dt-right'},
        {data: 3},{data: 5},{data: 7},
    ],
        order: [2, 'desc'],
});

$('#purchasetable tbody').on('click', 'td.details-control', function () {
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


function deleteForm(id) {
    swal({
      title: "Are You Sure ?",
      text: "Erased data, cannot be back",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
      if (result.value) {
            $.ajax({
              url : "/purchase/"+id,
              type : "POST",
              data: {_method: 'DELETE'},
              beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
              success : function(data){
              table.ajax.reload();
              swal("Success","Data is erased","success");

            },
              error : function(data) {
              swal("Error","Cannot erased data !","error");
            }
            });
          }
      });
}

</script>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#produk-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image_path").change(function(){
        readURL(this);
    });

</script>

@include('flash')

@endsection
