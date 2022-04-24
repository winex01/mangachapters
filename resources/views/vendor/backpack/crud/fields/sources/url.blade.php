@include('crud::fields.text')

@push('crud_fields_scripts')
<script>
    $('input[name="{{ $field['name'] }}"]').focusout(function (e) { 
        e.preventDefault();

        var url = $(this).val();
        var segments = url.split( '/' );
        var scanFilterDesc = segments[2];
        var filterId = $('select[name="scan_filter_id"] option:contains("'+scanFilterDesc+'")').val();

        $('select[name="scan_filter_id"]').val(filterId).trigger('change');

    });
</script>
@endpush