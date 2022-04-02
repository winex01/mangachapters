@include('crud::fields.radio')

@push('crud_fields_scripts')
<script type="text/javascript">

	if ( $('input[name="{{ $field['name'] }}"]').val() == {{ $field['show_fields_if_value'] }} ) {
		$('.{{ $field['toggle_class'] }}').show();
	}else {
		$('.{{ $field['toggle_class'] }}').hide();
	}

	$('input[name="{{ $field['attributes']['name'] }}"]').change(function(event) {
		if (this.value == {{ $field['show_fields_if_value'] }}) {
			$('.{{ $field['toggle_class'] }}').show();
		}else {
			$('.{{ $field['toggle_class'] }}').hide();
		} 
	});
</script>
@endpush