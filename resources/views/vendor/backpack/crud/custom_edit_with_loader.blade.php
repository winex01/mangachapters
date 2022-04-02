@push('after_scripts')
<script>
    $("[action='"+"{{ url($crud->route.'/'.$entry->getKey()) }}"+"']").submit(function( event ) {
        swalLoader('Updating '+"{{ convertColumnToHumanReadable($crud->model->model) }}"+'...');
    });
</script>
@endpush

@include('crud::edit')