@if ($crud->hasAccess('scan'))
	<a 
        href="javascript:void(0)" 
        onclick="scanEntries(this)" 
        class="btn btn-warning" 
        data-style="zoom-in">
            <span class="ladda-label">
                <i class="la la-plus"></i> 
                {{ trans('lang.scan_operation_button') }} {{ $crud->entity_name }}
            </span>
    </a>
@endif

@push('after_scripts')
<script>
	if (typeof scanEntries != 'function') {
	  function scanEntries(button) {

          swalLoader('Scanning Chapter...');

          $.ajax({
              type: "post",
              url: "{{ url($crud->route) }}/scan",
              success: function (response) {
                console.log(response);
                if (response.error) {
                    swalError();
                    new Noty({
                        type: "warning",
                        text: "<strong>{!! __('Scanned Error') !!}</strong><br>{!! __('Whoops something went wrong.') !!}"
                    }).show();			          	  
                } else {
                    // Show a success notification bubble
                    swalSuccess();
                }
                crud.table.ajax.reload();                 
              },
              error: function () {
                swalError();
              }
          });

      } // end function scanEntries
	}
</script>
<script src="{{ asset('js/swal2_helper.js') }}"></script>
@endpush


