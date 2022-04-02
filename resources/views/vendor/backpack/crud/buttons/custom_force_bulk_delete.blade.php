@if ($crud->hasAccess('forceBulkDelete') && $crud->get('list.bulkActions'))
	<a href="javascript:void(0)" onclick="forceBulkDeleteEntries(this)" class="btn btn-sm btn-secondary bulk-button btn-danger" data-toggle="tooltip" title="{{ trans('backpack::crud.force_delete') }}"><i class="la la-trash"></i></a>
@endif

@push('after_scripts')
<script>
	if (typeof forceBulkDeleteEntries != 'function') {
	  function forceBulkDeleteEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
	      	new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = ("{!! trans('backpack::crud.bulk_delete_are_you_sure') !!}").replace(":number", crud.checkedItems.length);
	      var button = $(this);

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
			  text: message,
			  icon: 'warning',
			  showCancelButton: true,
			  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",
			  confirmButtonText: "{!! trans('backpack::crud.force_delete') !!}",
			  reverseButtons: true,
			}).then((value) => {
				if (value.isConfirmed) {
					var ajax_calls = [];
					var delete_route = "{{ url($crud->route) }}/forceBulkDelete";

					// submit an AJAX delete call
					$.ajax({
						url: delete_route,
						type: 'POST',
						data: { entries: crud.checkedItems },
						success: function(result) {
							if (Array.isArray(result)) {
							  // Show a success notification bubble
							  new Noty({
							    type: "success",
							    text: "<strong>{!! trans('backpack::crud.bulk_delete_sucess_title') !!}</strong><br>"+crud.checkedItems.length+"{!! trans('backpack::crud.bulk_delete_sucess_message') !!}"
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
								  } else {
								  	// Show a warning notification bubble
									new Noty({
										type: "warning",
										text: "<strong>{!! trans('backpack::crud.bulk_delete_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_error_message') !!}"
									}).show();
								  }			          	  
							}

						  	crud.checkedItems = [];
							crud.table.ajax.reload();
						},
						error: function(result) {

							// refresh crud table and remove check items bisag error
							crud.checkedItems = [];
							crud.table.ajax.reload();

							// Show an alert with the result
							new Noty({
								type: "warning",
								text: "<strong>{!! trans('backpack::crud.bulk_delete_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_delete_error_message') !!}"
							}).show();
						}
					});
				}
			});
      }
	}
</script>
@endpush
