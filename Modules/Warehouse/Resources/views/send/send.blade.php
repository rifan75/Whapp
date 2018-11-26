@extends('adminlte::page')

@section('title', 'WApp|Warehouse')

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
<section class="content-header">
    <!--section starts-->
    <h1>Daftar Pengiriman dari {{$warehouse->name}}<small>&nbsp;<span id="waktu"></span></small></h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active"><a href="#">{{$warehouse->name}}</a></li>
    </ol>
</section>
<!--section ends-->


<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header">
          <a href="/warehouse/addsend/{{$warehouse->code}}" class="btn btn-success"><i class="fa fa-plus-circle"></i>&nbsp;Send Items</a>
      <!-- /.box-header -->

        </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form method="post" id="formkirimtable">
          <meta id="token" name="token" content="{{ csrf_token() }}">
        <table id="kirimtable" class="table table-bordered table-hover">
          <input id="idlok" type="hidden" value="{{$warehouse->code}}">
          <thead style="vertical-align:middle">
            <tr bgcolor="#D3D3D3" >
              <th ></th>
              <th>No</th>
              <th>Id</th>
              <th style="text-align:center">No Surat</th>
              <th style="text-align:center">Tgl Surat</th>
              <th style="text-align:center">Kirim Ke</th>
              <th style="text-align:center">Tanggal Sampai</th>
              <th style="text-align:center">Perekam</th>
              <th style="text-align:center">Aksi</th>
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
    $.each($(d.skuproduct_id),function(key,value){
        i++;
        trs+='<tr><td style="text-align:center">'+i+'</td><td>'+d.skuproduct_code[key]+'</td><td>'+value+'</td><td style="text-align:right">'+d.quantity[key]+'</td><td style="text-align:center">'
        +d.measure[key]+'</td></tr>';
        //loop through each product and append it to trs and am hoping that number of price
        //values in array will be equal to number of products
    })
    // `d` is the original data object for the row
    return '<table class="table table-border table-hover">'+
           '<thead>'+
              '<th style="text-align:center; width:5px">No</th><th style="text-align:center"> Kode Barang/ Barang Siap Jual</th><th style="text-align:center">Barang/ Barang Siap Jual</th><th style="text-align:center">Jumlah</th>'+
              '<th style="text-align:center">Satuan</th>'+
           '</thead><tbody>'
               + trs +
        '</tbody></table>';
}
var idlok = $('#idlok').val();
var table = $('#kirimtable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/warehouse/getsend/"+idlok},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, orderable: false},{data: 1,  visible: false},{data: 2},{data: 4, className: 'dt-center'},
        {data: 8, className: 'dt-center'},{data: 7, className: 'dt-center'},{data: 6, className: 'dt-center'},
        {data: 10},
    ],
        order: [2, 'desc'],
});

$('#kirimtable tbody').on('click', 'td.details-control', function () {
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

//Menghapus data
function deleteForm(id) {
    swal({
      title: 'Apakah, anda yakin ?',
      text: "Data yg dihapus, tidak bisa dikembalikan",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!'
    }).then((result) => {
      if (result.value) {
            $.ajax({
              url : "/kirim/"+id,
              type : "POST",
              data: {_method: 'DELETE'},
              beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
              success : function(data){
              table.ajax.reload();
              swal("Sukses","Data berhasil dihapus","success").catch(swal.noop);

            },
              error : function(data) {
              swal("Error","Tidak dapat menghapus data !","error").catch(swal.noop);
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
