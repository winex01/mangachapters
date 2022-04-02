@php
	$contentClass = isset($contentClass) ? $contentClass : $crud->getShowContentClass();
@endphp

<script type="text/javascript">
	function printData() {
		$('#action-row').hide();
		$('#print-div').removeClass();
		$('#print-div').addClass('col-md-12 col-lg-12');
		window.print();
		$('#action-row').show();
		$('#print-div').removeClass();
		$('#print-div').addClass('{{ $contentClass }}');
	}
</script>