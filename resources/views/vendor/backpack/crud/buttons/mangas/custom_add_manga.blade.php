@if ($crud->hasAccess('addManga'))
	<a 
        href="javascript:void(0)" 
        onclick="addMangaEntry()" 
        class="btn btn-info" 
        data-style="zoom-in">
            <span class="ladda-label">
                <i class="la la-plus"></i> 
                {{ trans('lang.mangas_add_button_operation') }} {{ $crud->entity_name }}
            </span>
    </a>
@endif

@push('after_scripts')
<script>
	if (typeof addMangaEntry != 'function') {
        // Function to show the modal dialog
        function addMangaEntry() {
            Swal.fire({
                title: 'Enter a URL',
                input: 'url',
                inputPlaceholder: 'https://example.com',
                inputAttributes: {
                    autocapitalize: 'off',
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                cancelButtonText: 'Cancel',
                inputValidator: (value) => {
                    if (!value || !value.match(/^https:\/\/\w+\.\w+/)) {
                        return 'Please enter a valid URL (e.g., https://example.com)';
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = result.value;
                    // Send the URL to the server for further processing using AJAX
                    sendDataToServer(url);
                }
            });
        }
	}

    // AJAX function to send data to the server using Fetch
    function sendDataToServer(url) {
            
        swalLoader('Loading...');

        $.ajax({
            type: "post",
            url: "{{ url($crud->route) }}/addManga",
            data: { url: url },
            success: function (response) {
                // console.log(response);
                if (response) {
                    if (response.already_exist) {
                        Swal.fire({
                            title: 'URL Already Exist!',
                            icon: "info",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }else if(response.invalid_url){
                        Swal.fire({
                            title: 'URL not supported yet or invalid!',
                            icon: "info",
                            timer: 2000,
                            showConfirmButton: false,
                        });
                    }else {
                        swalSuccess();
                    }
                } else {
                    swalError();
                    new Noty({
                        type: "warning",
                        text: "<strong>{!! __('Scanned Error') !!}</strong><br>{!! __('Whoops something went wrong.') !!}"
                    }).show();			          	  
                }
                crud.table.ajax.reload();                 
            },
            error: function () {
                swalError();
            }
        });
    }

</script>
<script src="{{ asset('js/swal2_helper.js') }}"></script>
@endpush