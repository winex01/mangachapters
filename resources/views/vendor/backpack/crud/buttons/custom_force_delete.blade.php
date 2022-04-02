@if ($crud->hasAccess('forceDelete'))
	<a href="javascript:void(0)" onclick="forceDeleteEntry(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/forceDelete') }}" class="btn btn-sm btn-link text-danger" data-button-type="forceDeleteEntry" data-toggle="tooltip" title="{!! trans('backpack::crud.force_delete') !!}"><i class="la la-trash"></i></a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof forceDeleteEntry != 'function') {
	  $("[data-button-type=forceDeleteEntry]").unbind('click');

	  function forceDeleteEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-danger ml-1',
		    cancelButton: 'btn btn-secondary'
		  },
		  buttonsStyling: false
		});

      	// show confirm message
		swalWithBootstrapButtons.fire({
		  title: "{!! trans('backpack::crud.force_delete_warning') !!}",
		  text: "{!! trans('backpack::crud.delete_confirm') !!}",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: "{!! trans('backpack::crud.force_delete') !!}",
		  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",
		  reverseButtons: true,
		}).then((value) => {
		  if (value.isConfirmed) {
	  		$.ajax({
		      url: route,
		      type: 'DELETE',
		      success: function(result) {
		          if (result == 1) {
					  // Redraw the table
					  if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
						  crud.table.draw(false);
					  }

		          	  // Show a success notification bubble
		              new Noty({
	                    type: "success",
	                    text: "{!! '<strong>'.trans('backpack::crud.delete_confirmation_title').'</strong><br>'.trans('backpack::crud.delete_confirmation_message') !!}"
	                  }).show();

		              // Hide the modal, if any
		              $('.modal').modal('hide');
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
			              	title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
                            text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
			              	icon: "error",
			              	timer: 4000,
			              	showConfirmButton: false,
			              });
		          	  }			          	  
		          }

		         	// winex: if operation is show then redirect
					@include('crud::inc.custom_redirect_to_crud_route')
		      },
		      error: function(result) {
		          // Show an alert with the result
		          Swal.fire({
	              	title: "{!! trans('backpack::crud.delete_confirmation_not_title') !!}",
                    text: "{!! trans('backpack::crud.delete_confirmation_not_message') !!}",
	              	icon: "error",
	              	timer: 4000,
	              	showConfirmButton: false,
	              });
		      }
		  	}); // end ajax

		  }
		});//end swal


      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('forceDeleteEntry');
</script>
@if (!request()->ajax()) @endpush @endif