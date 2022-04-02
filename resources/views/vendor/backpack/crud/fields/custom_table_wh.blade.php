
@include('crud::fields.custom_table')

@push('crud_fields_scripts')
<script src="{{ asset('packages/moment/min/moment.min.js') }}"></script>
<script type="text/javascript">
	$('input[name="working_hours"]').parent().find('tbody').on('keyup click input change', function() {
		var tempWh = JSON.parse(
			$('input[name="working_hours"]').val()
		);
		tempWh = tempWh[0].start;
		
		if (tempWh){
			var deductHours = moment.duration("03:00:00"); // 3 hours default deduct
			var tempWh = moment("{{ date('Y-m-d') }}"+" "+tempWh+":00");
			tempWh.subtract(deductHours);
				
			$('input[name="relative_day_start"]').val(tempWh.format('HH:mm'));
		}
    });
</script>
@endpush