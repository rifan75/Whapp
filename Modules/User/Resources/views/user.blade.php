@extends('adminlte::page')

@section('title', 'WApp|User')

{{-- page level styles --}}
@section('css')
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
          <h3 class="box-title">User List</h3>
        </div>
        <div class="box-body">
          <form method="post" id="formuser">
						<meta id="token" name="token" content="{{ csrf_token() }}">
          <table id="usertable" class="table table-bordered table-hover">
            <thead>
            <tr>
							<th ></th>
              <th style="text-align:center">No</th>
              <th style="text-align:center">Id</th>
              <th style="text-align:center">name</th>
							<th style="text-align:center">email</th>
              <th style="text-align:center">level</th>
							<th style="text-align:center">active</th>
              <th style="text-align:center">action</th>
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
      <div class="box" id="form">
        <div class="box-header">
          <h3 id="juduluser" class="box-title">Input User</h3>
        </div>
        <div class="box-body">
          <div class="container-fluid add-product">
          <form id="userform" action="/user/register" method="post" enctype="multipart/form-data" data-toggle="validator">
            {{csrf_field()}}
            <input id="inputhidden" type='hidden' name='_method' value='POST'>
              <div class="row">
              <div class="form-group col-md-12">
              	<label for="name" class=" control-label">Name</label>
              	<input id="name" type="text" class="form-control" name="name"  required>
								@if ($errors->has('name'))
									<span class="help-block" style="color:red">
									<strong>{{ $errors->first('name')}}</strong>
									</span>
								@endif
              </div>
							<div class="form-group col-md-12">
              	<label for="email" class=" control-label">Email</label>
              	<input id="email" type="text" class="form-control" name="email"  required>
								@if ($errors->has('email'))
									<span class="help-block" style="color:red">
									<strong>{{ $errors->first('email')}}</strong>
									</span>
								@endif
              </div>
							<div class="form-group col-md-12" id="passworddiv">
              	<label for="password" class=" control-label">Password</label>
              	<input  type="password" class="form-control" id="password" name="password" >
								@if ($errors->has('password'))
									<span class="help-block" style="color:red">
									<strong>{{ $errors->first('password')}}</strong>
									</span>
								@endif
              </div>
							<div class="form-group col-md-12" id="password_confirmationdiv">
              	<label for="password_confirmation" class=" control-label">Confirm Password</label>
              	<input  type="password" class="form-control" name="password_confirmation"
								data-match="#password" >
              </div>
							<div class="form-group col-md-12" id="leveldiv">
								<label for="level">Level</label>
								<select name="level" id="level" class="form-control">
									@foreach($levels as $level)
										@if ($level->id == 1)
								        @continue
								    @endif
									  <option value="{{$level->id}}" id="{{$level->id}}">{{$level->name}}</option>
									@endforeach
								</select>
								@if ($errors->has('level'))
									<span class="help-block" style="color:red">
									<strong>{{ $errors->first('level')}}</strong>
									</span>
								@endif
							</div>
							<div class="form-group col-md-12" id="leveldiv">
								<input type="file" name="picture_path" id="picture"><br>
									@if ($errors->has('picture_path'))
					          <span class="help-block" style="color:red">
					          <strong>{{ $errors->first('picture_path')}}</strong>
					          </span>
					        @endif
				        <br>
								<p id="image_picture"></p>
							</div>
							<!-- <div id="image_picture">
							   <div class="form-group" >
							   </div>
							  </div> -->
							</div>
              <div class="row">
                <div class="form-group col-md-12">
          	    <input id="submit" type="submit" class="form-control btn btn-primary" value="Add User">
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.0.11/handlebars.min.js" ></script>
<script id="details-template" type="text/x-handlebars-template">
    <table class="table">
        <tr><td><image src="@{{5}}" width="200px"></td></tr>
    </table>
</script>
<script type="text/javascript">
var template = Handlebars.compile($("#details-template").html());
var table = $('#usertable').DataTable({
    processing: true,
    serverSide: true,
    dom: 'Bfrtip',
    buttons: ['csv', 'excel', 'pdf', 'print'],
    ajax: {"url" : "/user/getuser"},
    columns: [
        {"className":'details-control',"orderable":false,"searchable":false,"data":null,"defaultContent": ''},
        {data: 0, className: 'dt-center'},{data: 1, visible:false},{data: 2},
				{data: 3},{data: 4, className: 'dt-center'},{data: 7, className: 'dt-center'},
				{data: 6, orderable: false, className: 'dt-center'},
    ],
        aaSorting: [[0,'asc']]
});

$('#usertable tbody').on('click', 'td.details-control', function () {
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

$('#userform')[0].reset();

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
                                    '<img id="image_baru" src="" height="150" width="150"></img><br><br>'+
																'</div>');
});
console.log(window.location.hostname);
function editForm(id){
	document.getElementById('userform').reset();
  //$('#userform')[0].reset();
  $('#inputhidden').val('PATCH');
  $('#leveldiv').show();
	$('#hid1').remove();
  $.ajax({
    url : "/user/"+id+"/edit",
    type : "GET",
    dataType : "JSON",
    success : function(data){

       $('#juduluser').text('Edit User');
       $('#submit').val('Submit');
       $('#name').val(data.name);
			 $('#email').val(data.email);
			 $('#newpicture').remove();
			 $('#image_picture').append('<div class="form-group" id="newpicture">'+
	                                     '<img id="image_baru" src="'+window.location.protocol+'//'+window.location.hostname+'/'+data.picture_path+
																			 '" height="150" width="150"></img><br><br>'+
	 																'</div>');
			 $('#passworddiv').remove();
			 $('#password_confirmationdiv').remove();
			 if(data.level==1){
				$('#leveldiv').hide();
				$('#level').val('');
        $('#userform').append('<input id="hid1" type="hidden" name="fieldname" value="1" />');
			 }else{
				 $('select option[value= "'+data.level+'"]').attr("selected","selected");
		   }
			 $('#note').val(data.note);
       $('#userform').attr('action', '/user/'+id);
    },
    error : function() {
      swal("Error","Opps, something wrong !","error");
    },
  });
}

function deleteForm(id) {
	swal({
		title: 'Are You Sure ?',
		text: "Erased data, cannot be back",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, Delete!'
	}).then((result) => {
		if (result.value) {
					$.ajax({
						url : "/user/"+id,
						type : "POST",
						data: {_method: 'DELETE'},
						beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
						success : function(data){
						table.ajax.reload();
						swal("Sukses","Data is erased","success");
					},
						error : function(data) {
						swal("Error","Cannot erased data !","error");
					}
					});
			}
	});
}
function editAct(id,act) {
  swal({
    title: 'Are You Sure ?',
    text: "This Will Change Active Mode User",
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, I am Sure!'
  }).then((result) => {
		if (result.value) {
      $.ajax({
        url : "/useract/"+id+"/"+act,
        type : "PATCH",
        data: {_method: 'UPDATE'},
        beforeSend: function(xhr){xhr.setRequestHeader('X-CSRF-TOKEN', $("#token").attr('content'));},
        success : function(data){
        table.ajax.reload();
        swal("Success","Active mode user is changed","success");
      },
        error : function(data) {
        swal("Error","Opps, something wrong !","error");
      }
      });
		}
  });
}
</script>
@include('flash')

@stop
