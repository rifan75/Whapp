@if (session()->has('flash_message'))

	<script>

 	swal({

		title : "{{ session('flash_message.title') }}",
  		text  :	"{{ session('flash_message.message') }}",
		type  :	"{{ session('flash_message.level') }}",
		timer : 1700,
		showConfirmButton: false

	});

	</script>
@endif


@if (session()->has('flash_message_overlay'))

	<script>

 	swal({

		title : "{{ session('flash_message_overlay.title') }}",
  		text  :	"{{ session('flash_message_overlay.message') }}",
		type  :	"{{ session('flash_message_overlay.level') }}",
		confirmButtonText : 'Okay'

	});

	</script>

@endif

@if (session()->has('flash_confirm_print'))

	<script>
	var id = '{{ Session::get('id')}}';
	console.log(id);
	swal({
		title: "{{ session('flash_confirm_print.message') }}",
		text: "Do You Want To Print ?",
		type: "{{ session('flash_confirm_print.level') }}",
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		confirmButtonText: 'Ya, Print It!'
	}).then((result) => {
					if (result.value) {
			    	window.open('/purchase/'+id+'/print', '_blank');
				  } else {
				  }

				});
	</script>

@endif
