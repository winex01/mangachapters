@include('crud::filters.simple')

@push('after_scripts')
	<script type="text/javascript">
		{{-- hide bottom buttons if history filter is active --}}
		$("li[filter-type]").click(function(e){	

		    if ($("li[filter-type='custom_simple_hide_bottom_buttons']").hasClass('active')) {

    			$('.bulk-button').hide();

		    }else {

	    		$('.bulk-button').show();

		    }

		});

		{{-- if Remove filters is click --}}
		$('#remove_filters_button').click(function (){
			$('.bulk-button').show();
		});
	</script>
@endpush