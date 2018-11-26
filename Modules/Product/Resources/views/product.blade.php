@extends('adminlte::page')

@section('title', 'WApp|Product')

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
          <a onclick="addForm()" class="btn btn-success"><i class="fa fa-plus-circle"></i>&nbsp;Add Product</a>
        </div>
      <!-- /.box-header -->
      <div class="box-body">
        <form method="post" id="formproducttable">
          <meta id="token" name="token" content="{{ csrf_token() }}">
        <table id="producttable" class="table table-bordered table-hover" style="width:100%">
          <thead style="vertical-align:middle">
            <tr bgcolor="#D3D3D3" >
              <th ></th>
              <th>No</th>
              <th>Id</th>
              <th style="text-align:center">Code</th>
              <th style="text-align:center">Name</th>
              <th style="text-align:center">Brand</th>
              <th style="text-align:center">Model</th>
              <th style="text-align:center">Colour</th>
              <th style="text-align:center">Warning</th>
              <th style="text-align:center">Guarantee</th>
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
   @include('product::formproduct')
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

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>Recorder :  @{{10}}</td></tr>
        <tr><td><image src="@{{11}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var table = $('#producttable').DataTable({
    processing: true,
    dom: 'Bfrtip',
    buttons: [{
                extend: 'csvHtml5',
                exportOptions: {
                    columns: [ 1,3,4,5,6,7,8,9,10 ]
                }
    },
    {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: [ 1,3,4,5,6,7,8,9,10 ]
                }
    },
    {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: [ 1,3,4,5,6,7,8,9,10 ]
                },
                title: 'Product List'
    },
    {
                extend: 'print',
                exportOptions: {
                    columns: [ 1,3,4,5,6,7,8,9,10 ]
                },
                title: 'Product List'
    }],
    ajax: {"url" : "/product/getproduct"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0},{data: 1,  visible: false},{data: 2},{data: 3},{data: 4},{data: 5},{data: 6},
        {data: 7},{data: 8},{data: 9}
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
// Showing add product Form
function addForm(){
  $('#modal-form').modal('show');
  $('#form')[0].reset();
  $('.modal-title').text('Add Product');
}

function editimage(id,url){
//  document.getElementById('formeditpicture').reset();
  $("#formeditpicture")[0].reset();
  $('#modalimage-form').modal('show');
  $('#hidpict').val(id);
  $('#imagepict').attr('src',url);
  $('#newpicture').remove();
  $('#titleimage').text('Change Image');
}
function readURL(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#picture').change(function(){
    readURL(this);
    $('#newpicture').remove();
    $('#image_picture').append('<div class="form-group" id="newpicture">'+
                                  '<label>New Image</label><br>'+
                                    '<img id="image_baru" src="" height="150" width="150"></img><br><br></div>');
});
function generatepi(){
  var product_name = $('#name').val();
  $.ajax({
    url : "product/productgen/"+product_name,
    type : "GET",
    dataType : "JSON",
    success : function(data){
      $('#code').val(data);
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}
// Showing add product Form
function editForm(id){
  $('#form')[0].reset();
  $('#inputhidden').val('PATCH');
  $('#modal-form').modal('show');
  $.ajax({
    url : "product/"+id+"/edit",
    type : "GET",
    dataType : "JSON",
    success : function(data){
      $('.modal-title').text('Edit Product');
      $('#submit').val('Edit Product');
      $('#name').val(data.name);
      $('#measure').val(data.measure);
      $('#brand').val(data.brand);
      $('#model').val(data.model);
      $('#color').val(data.color);
      $('#hazardwarning').val(data.hazardwarning);
      $('#code').val(data.code);
      $('#warranty_type').val(data.warranty_type);
      $('#form').attr('action', 'product/'+id);
    },
    error : function() {
      swal("Error","Cannot Show Data !","error");
    },
  });
}
//Menghapus data
function deleteForm(id) {
    swal({
      title: 'Are You Sure ?',
      text: "Erased data, cannot be back",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, Delete!'
    }).then((result) => {
      if (result.value) {
            $.ajax({
              url : "product/"+id,
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
@include('flash')
@endsection
