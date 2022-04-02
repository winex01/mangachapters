@push('after_scripts')
	<script type="text/javascript">
		{{-- rename breadcrumb Admin to Home --}}
		// var el = $('.breadcrumb-item.text-capitalize').first().find('a').html('Home');
		
		{{-- fix modal tab-index --}}
		$(document).on('show.bs.modal', '.modal', function () {
	       $(this).appendTo('body');
	    });

	</script>
@endpush