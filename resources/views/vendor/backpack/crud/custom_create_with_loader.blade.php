@push('after_scripts')
<script>
    $("[action='"+"{{ url($crud->route) }}"+"']").submit(function( event ) {
        swalLoader('Creating '+"{{ convertColumnToHumanReadable($crud->model->model) }}"+'...');
    });
</script>
@endpush

@include('crud::create')