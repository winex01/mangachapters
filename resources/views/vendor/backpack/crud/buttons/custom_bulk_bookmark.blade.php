@if ($crud->hasAccess('bulkBookmark') && $crud->get('list.bulkActions'))
	<a 
        href="javascript:void(0)" 
        onclick="bulkBookmarkEntries(this)" 
        class="btn btn-success" 
        data-toggle="tooltip" 
        title="{{ __('Check checkbox beside the image.') }}"
    >
        <i  class="las la-heart"></i>
        {{ __('Bulk Bookmark') }}
    </a>
@endif

@push('after_scripts')
<script>
	if (typeof bulkBookmarkEntries != 'function') {
	  function bulkBookmarkEntries(button) {

	      if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
	      {
	      	new Noty({
	          type: "warning",
	          text: "<strong>{!! trans('backpack::crud.bulk_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.bulk_no_entries_selected_message') !!}"
	        }).show();

	      	return;
	      }

	      var message = ("{!! trans('backpack::crud.bulk_bookmark_are_you_sure') !!}").replace(":number", crud.checkedItems.length);
	      var button = $(this);

		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success ml-1',
		    cancelButton: 'btn btn-secondary'
		  },
		  buttonsStyling: false
		});

      	// show confirm message
		swalWithBootstrapButtons.fire({
		  text: message,
		  icon: 'info',
		  showCancelButton: true,
		  confirmButtonText: "{!! trans('backpack::crud.yes_please') !!}",
		  cancelButtonText: "{!! trans('backpack::crud.cancel') !!}",
		  reverseButtons: true,
		}).then((result) => {
		  if (result.isConfirmed) {
			var ajax_calls = [];
			var route = "{{ url($crud->route) }}/bulkBookmark";

			$.ajax({
				url: route,
				type: 'POST',
				data: { entries: crud.checkedItems },
				success: function(result) {
                    // console.log(result);
					if (result) {
					  // Show a success notification bubble
					  new Noty({
					    type: "success",
					    text: "<strong>{!! trans('backpack::crud.bulk_bookmark_success_title') !!}</strong><br>"+crud.checkedItems.length+"{!! trans('backpack::crud.bulk_bookmark_success_message') !!}"
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
								text: "<strong>{!! trans('backpack::crud.bulk_bookmark_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_bookmark_error_message') !!}"
							}).show();
						  }			          	  
					}

				  	crud.checkedItems = [];
					  	crud.table.ajax.reload();
				},
				error: function(result) {
					// Show an alert with the result
					new Noty({
						type: "warning",
						text: "<strong>{!! trans('backpack::crud.bulk_bookmark_error_title') !!}</strong><br>{!! trans('backpack::crud.bulk_bookmark_error_message') !!}"
					}).show();
				}
			}); // end ajax

		  }
		});//end swal

      } // end function bulkBookmarkEntries
	}
</script>
@endpush
