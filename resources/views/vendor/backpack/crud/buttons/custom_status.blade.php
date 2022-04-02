@if ($crud->hasAccess('status'))
	<a href="javascript:void(0)" 
		class="btn btn-sm btn-link" 
		onclick="statusEntry(this)" 
		data-route="{{ url($crud->route.'/'.$entry->getKey().'/status') }}" 
		data-current-status="{{ $entry->status }}" 
		data-button-type="status" 
		data-toggle="tooltip" 
		title="{{ trans('backpack::crud.status') }}">
		<i class="las la-hand-pointer"></i>
	</a>
@endif 

{{-- @dump($entry->status) --}}
{{-- @dump(url($crud->route)) --}}

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof statusEntry != 'function') {
	  $("[data-button-type=status]").unbind('click');

	  function statusEntry(button) {
		// e.preventDefault();
		var button = $(button);
		var route = button.attr('data-route');
		var row = $("#crudTable a[data-route='"+route+"']").closest('tr');

		{{-- [0,1,2])) { // pending, approved, denied --}}
		var denyButton = false;
		var confirmButton = false;
		var currentStatus = button.attr('data-current-status');

		if (currentStatus == 1) {
			denyButton = true;
		}else if (currentStatus == 2) {
			confirmButton = true;
		}else {
			denyButton = true;
			confirmButton = true;
		}


		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success ml-1',
		    cancelButton: 'btn btn-secondary',
		    denyButton: 'btn btn-danger ml-1'
		  },
		  buttonsStyling: false
		});


      	// show confirm message
		swalWithBootstrapButtons.fire({
		  title: "{!! trans('backpack::base.warning') !!}",
		  text: "{!! trans('backpack::crud.status_confirm') !!}",
		  icon: 'warning',
		  
		  showCancelButton: true,
		  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",

		  showConfirmButton: confirmButton,
		  confirmButtonText: "{!! trans('backpack::crud.status_button') !!}",
		  
		  showDenyButton: denyButton,
		  denyButtonText: "{!! trans('backpack::crud.status_button_denied') !!}",
		  
		  reverseButtons: true,
		}).then((value) => {
			if (value.isConfirmed || value.isDenied) {
				var status;

				if (value.isConfirmed) {
					status = 1; 
				}else {
					status = 2;
				}

				$.ajax({
			      url: route,
			      type: 'POST',
			      data: {
			      	status: status
			      },
			      success: function(result) {
			      	// console.log(result);

			          if (result == 1) {
			          	  // Show a success notification bubble
			              new Noty({
		                    type: "success",
		                    text: "{!! '<strong>'.trans('backpack::crud.status_confirmation_title').'</strong><br>'.trans('backpack::crud.status_confirmation_message') !!}"
		                  }).show();

			              // Hide the modal, if any
			              $('.modal').modal('hide');

			              // reload table
			              if (typeof crud !== 'undefined') {
		                    crud.table.ajax.reload();
		                  }

			          } else if (result.validationFail == true) {
		          		Swal.fire({
			              	title: "{!! trans('backpack::crud.status_confirmation_not_title') !!}",
                            text: result.validationMsgText,
			              	icon: "error",
			              	timer: 4000,
			              	showConfirmButton: false,
			              });

          				new Noty({
						    type: "error",
						    text: result.validationMsgText,
						}).show();
			          } else {
			              // if the result is an array, it means 
			              // we have notification bubbles to show
			          	  if (result instanceof Object) {
			          	  	// trigger one or more bubble notifications 
			          	  	Object.entries(result).forEach(function(entry, index) {
			          	  	  var type = entry[0];
			          	  	  entry[1].forEach(function(message, i) {
					          	  new Noty({
				                    type: type,
				                    text: message
				                  }).show();
			          	  	  });
			          	  	});
			          	  } else {// Show an error alert
				              Swal.fire({
				              	title: "{!! trans('backpack::crud.status_confirmation_not_title') !!}",
	                            text: "{!! trans('backpack::crud.status_confirmation_not_message') !!}",
				              	icon: "error",
				              	timer: 4000,
				              	showConfirmButton: false,
				              });
			          	  }			          	  
			          }

			          // if operation is show then redirect
			          @include('crud::inc.custom_redirect_to_crud_route')
			      },
			      error: function(result) {
			          // Show an alert with the result
			          Swal.fire({
		              	title: "{!! trans('backpack::crud.status_confirmation_not_title') !!}",
                        text: "{!! trans('backpack::crud.status_confirmation_not_message') !!}",
		              	icon: "error",
		              	timer: 4000,
		              	showConfirmButton: false,
		              });
			      }
			  });
			}// end if value
		});

      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('statusEntry');
</script>
@if (!request()->ajax()) @endpush @endif
