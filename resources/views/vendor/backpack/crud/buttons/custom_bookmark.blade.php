@if ($crud->hasAccess('bookmark'))
	<a 
        href="javascript:void(0)" 
        data-toggle="tooltip" 
        title="{{ __('Bookmark') }}" 
        onclick="bookmarkEntry(this)" 
        data-route="{{ url($crud->route.'/'.$entry->getKey()).'/bookmark' }}" 
        class="btn btn-sm btn-link text-success" 
        data-button-type="bookmark"
    >
        <i class="las la-heart"></i>
    </a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>

	if (typeof bookmarkEntry != 'function') {
	  $("[data-button-type=bookmark]").unbind('click');

	  function bookmarkEntry(button) {
		// ask for confirmation before deleting an item
		// e.preventDefault();
		var route = $(button).attr('data-route');

		$.ajax({
            url: route,
            type: 'post',
            success: function(result) {
                console.log(result);
                if (result == 1) {
                    // Redraw the table
                    if (typeof crud != 'undefined' && typeof crud.table != 'undefined') {
                        crud.table.draw(false);
                    }

                    // Show a success notification bubble
                    new Noty({
                    type: "success",
                    text: "{!! '<strong>'.trans('backpack::crud.bookmark_confirmation_title').'</strong><br>'.trans('backpack::crud.bookmark_confirmation_message') !!}"
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
                        title: "{!! trans('backpack::crud.bookmark_confirmation_not_title') !!}",
                        text: "{!! trans('backpack::crud.bookmark_confirmation_not_message') !!}",
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
                title: "{!! trans('backpack::crud.bookmark_confirmation_not_title') !!}",
                text: "{!! trans('backpack::crud.bookmark_confirmation_not_message') !!}",
                icon: "error",
                timer: 4000,
                showConfirmButton: false,
                });
            }
        }); // end ajax


      }
	}

	// make it so that the function above is run after each DataTable draw event
	// crud.addFunctionToDataTablesDrawEventQueue('bookmarkEntry');
</script>
@if (!request()->ajax()) @endpush @endif