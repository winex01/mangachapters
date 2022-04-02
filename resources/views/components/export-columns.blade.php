@push('after_scripts')
	<script type="text/javascript">
		{{-- dropdown with checkbox --}}
		var exportColumns = @json($exportColumns);
		$( '.dropdown-menu a.export-link' ).on( 'click', function( event ) {

		   var $target = $( event.currentTarget ),
		       val = $target.attr( 'data-value' ),
		       $inp = $target.find( 'input' ),
		       idx;

		   if ( ( idx = exportColumns.indexOf( val ) ) > -1 ) {
		      exportColumns.splice( idx, 1 );
		      setTimeout( function() { $inp.prop( 'checked', false ) }, 0);
		   } else {
		      exportColumns.push( val );
		      setTimeout( function() { $inp.prop( 'checked', true ) }, 0);
		   }

		   $( event.target ).blur();
		      
		   // console.log( exportColumns ); 
		   return false;
		});

	</script>
@endpush