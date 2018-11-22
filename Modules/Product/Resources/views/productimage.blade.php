@extends('adminlte::page')

@section('title', 'WApp|Product')

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
			<div class="box">
				<div class="panel-body">
					<div class="row">
  					 <div class="col-md-3">
               <table id="imagetable" class="table table-hover">
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
						  <div class="col-md-9">
                <form id="formeditimage" class="form-horizontal" enctype="multipart/form-data" style="width:100%" role="form"  method="post" action="/product/{{$id}}/editimage">
              	{{csrf_field()}}
              	<input id="inputhidden" type='hidden' name='_method' value='PATCH'><br>
                <div class="row">
      					  <div class="col-md-3">
      						  <div id="image1_picture">
      						   <div class="form-group" >
      						     <label>Recent Image 1</label><br>
      						     <img id="image1_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
      						   </div>
      						  </div>
      						  <input type="file" name="image1" id="image1"><br>

      						        @if ($errors->has('image1'))
      						          <span class="help-block" style="color:red">
      						          <strong>{{ $errors->first('image1')}}</strong>
      						          </span>
      						        @endif
      						        <br>
      					  </div>
        					<div class="col-md-3">
        						<div id="image2_picture">
        						 <div class="form-group" >
        							 <label>Recent Image 2</label><br>
        							 <img id="image2_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
        						 </div>
        						</div>
        						<input type="file" name="image2" id="image2"><br>

        									@if ($errors->has('image2'))
        										<span class="help-block" style="color:red">
        										<strong>{{ $errors->first('image2')}}</strong>
        										</span>
        									@endif
        									<br>
        					</div>
        					<div class="col-md-3">
        						<div id="image3_picture">
        						 <div class="form-group" >
        							 <label>Recent Image 3</label><br>
        							 <img id="image3_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
        						 </div>
        						</div>
        						<input type="file" name="image3" id="image3"><br>

        									@if ($errors->has('image3'))
        										<span class="help-block" style="color:red">
        										<strong>{{ $errors->first('image3')}}</strong>
        										</span>
        									@endif
        									<br>
        					</div>
        					<div class="col-md-3">
        						<div id="image4_picture">
        						 <div class="form-group" >
        							 <label>Recent Image 4</label><br>
        							 <img id="image4_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
        						 </div>
        						</div>
        						<input type="file" name="image4" id="image4"><br>

        									@if ($errors->has('image4'))
        										<span class="help-block" style="color:red">
        										<strong>{{ $errors->first('image4')}}</strong>
        										</span>
        									@endif
        									<br>
        					</div>
        	       </div>
                 <div class="row">
                   <div class="col-md-3">
                     <div id="image5_picture">
                      <div class="form-group" >
                        <label>Recent Image 5</label><br>
                        <img id="image5_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
                      </div>
                     </div>
                     <input type="file" name="image5" id="image5"><br>

                           @if ($errors->has('image5'))
                             <span class="help-block" style="color:red">
                             <strong>{{ $errors->first('image5')}}</strong>
                             </span>
                           @endif
                           <br>
                 </div>
                 <div class="col-md-3">
                   <div id="image6_picture">
                    <div class="form-group" >
                      <label>Recent Image 6</label><br>
                      <img id="image6_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
                    </div>
                   </div>
                   <input type="file" name="image6" id="image6"><br>

                         @if ($errors->has('image6'))
                           <span class="help-block" style="color:red">
                           <strong>{{ $errors->first('image6')}}</strong>
                           </span>
                         @endif
                         <br>
                 </div>
                 <div class="col-md-3">
                   <div id="image7_picture">
                    <div class="form-group" >
                      <label>Recent Image 7</label><br>
                      <img id="image7_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
                    </div>
                   </div>
                   <input type="file" name="image7" id="image7"><br>

                         @if ($errors->has('image7'))
                           <span class="help-block" style="color:red">
                           <strong>{{ $errors->first('image7')}}</strong>
                           </span>
                         @endif
                         <br>
                 </div>
                 <div class="col-md-3">
                   <div id="image8_picture">
                    <div class="form-group" >
                      <label>Recent Image 8</label><br>
                      <img id="image8_recent" src="{{$url}}/products/images/product.png" style="margin-right:20px" height="150" width="150"></img>
                    </div>
                   </div>
                   <input type="file" name="image8" id="image8"><br>

                         @if ($errors->has('image8'))
                           <span class="help-block" style="color:red">
                           <strong>{{ $errors->first('image8')}}</strong>
                           </span>
                         @endif
                         <br>
                 </div>
               </div>
               <div class="row">
               <button id="submit" type="submit" class="btn btn-success col-md-8" style="float:none;text-align: center;">Submit</button>
             </div>
               </form>
  			        </div>
    		       </div>
	            </div>
						 </div>
					 </div>
				 </div>
    </div>
  </div>
</section>
@stop

{{-- page level scripts --}}
@section('js')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td>@{{3}}</td></tr>
        <tr><td><image src="@{{5}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var table = $('#imagetable').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false,
    pageLength: 10,
    pagingType: "simple",
    ajax: {"url" : "/product/getproductimage"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, visible: false},
        {data: 2, orderable: false},
        {data: 4, orderable: false},
    ],
        order: [0, 'desc'],
});

$('#imagetable tbody').on('click', 'td.details-control', function () {
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

$('#imagetable tbody').on( 'click', '#clicktabel', function () {
    var id = table.row($(this).closest('tr')).data()[1];
    var image1_recent = table.row($(this).closest('tr')).data()[5];
    var image2_recent = table.row($(this).closest('tr')).data()[6];
    var image3_recent = table.row($(this).closest('tr')).data()[7];
		var image4_recent = table.row($(this).closest('tr')).data()[8];
    var image5_recent = table.row($(this).closest('tr')).data()[9];
    var image6_recent = table.row($(this).closest('tr')).data()[10];
    var image7_recent = table.row($(this).closest('tr')).data()[11];
    var image8_recent = table.row($(this).closest('tr')).data()[12];
    console.log(id);
    $('#hidpict').val(id);
    $('#image1_recent').attr('src',image1_recent);
    $('#image2_recent').attr('src',image2_recent);
    $('#image3_recent').attr('src',image3_recent);
    $('#image4_recent').attr('src',image4_recent);
		$('#image5_recent').attr('src',image5_recent);
		$('#image6_recent').attr('src',image6_recent);
		$('#image7_recent').attr('src',image7_recent);
		$('#image8_recent').attr('src',image8_recent);
		$('#formeditimage').attr('action', '/product/'+id+'/editimage');
} );

function readURL1(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image1_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image1').change(function(){
    readURL1(this);
    $('#newimage1').remove();
    $('#image1_picture').append('<div class="form-group" id="newimage1">'+
                                  '<label>New Image 1</label><br>'+
                                    '<img id="image1_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL2(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image2_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image2').change(function(){
    readURL2(this);
    $('#newimage2').remove();
    $('#image2_picture').append('<div class="form-group" id="newimage2">'+
                                  '<label>New Image 2</label><br>'+
                                    '<img id="image2_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL3(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image3_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image3').change(function(){
    readURL3(this);
    $('#newimage3').remove();
    $('#image3_picture').append('<div class="form-group" id="newimage3">'+
                                  '<label>New Image 3</label><br>'+
                                    '<img id="image3_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL4(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image4_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image4').change(function(){
    readURL4(this);
    $('#newimage4').remove();
    $('#image4_picture').append('<div class="form-group" id="newimage4">'+
                                  '<label>New Image 4</label><br>'+
                                    '<img id="image4_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL5(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image5_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image5').change(function(){
    readURL5(this);
    $('#newimage5').remove();
    $('#image5_picture').append('<div class="form-group" id="newimage5">'+
                                  '<label>New Image 5</label><br>'+
                                    '<img id="image5_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL6(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image6_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image6').change(function(){
    readURL6(this);
    $('#newimage6').remove();
    $('#image6_picture').append('<div class="form-group" id="newimage6">'+
                                  '<label>New Image 6</label><br>'+
                                    '<img id="image6_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL7(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image7_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image7').change(function(){
    readURL7(this);
    $('#newimage7').remove();
    $('#image7_picture').append('<div class="form-group" id="newimage7">'+
                                  '<label>New Image 7</label><br>'+
                                    '<img id="image7_baru" src="" height="150" width="150"></img><br><br></div>');
});
function readURL8(input) {
              if (input.files && input.files[0]) {
                  var reader = new FileReader();
                  reader.onload = function (e) {
                      $('#image8_baru').attr('src', e.target.result);
                  }
                  reader.readAsDataURL(input.files[0]);

              }
          }
$('#image8').change(function(){
    readURL8(this);
    $('#newimage8').remove();
    $('#image8_picture').append('<div class="form-group" id="newimage8">'+
                                  '<label>New Image 8</label><br>'+
                                    '<img id="image8_baru" src="" height="150" width="150"></img><br><br></div>');
});
$('#formeditimage')[0].reset();

</script>
@include('flash')

@stop
