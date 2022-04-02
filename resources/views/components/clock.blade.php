<li class="nav-item px-3 ml-2"><a class="nav-link text-white" href="#">
	<span class="btn btn-outline-secondary clock" id="clock" title="{{ __('Server Time') }}">{{ date('j. F  Y - g : i : s A') }}</span>
</a></li> 
@push('after_scripts')
<script src="{{ asset('packages/moment/min/moment.min.js') }}"></script>
{{--  --}}
<script type="text/javascript">
	var crClockInit1 = null;
	var crClockInterval = null;
	function crInitClock() {
	    crClockInit1 = setInterval(function() {
	        if (moment().format("SSS") <= 40) {
	            clearInterval(crClockInit1);
	            crStartClockNow();
	        }
	    }, 30);
	}

   	var serverOffset = moment('{{ serverDateTime() }}').diff(new Date());

	function currentServerDate() {
    	return moment().add(serverOffset, 'milliseconds');
	}

	function crStartClockNow() {
	    crClockInterval = setInterval(function() {
	        $('#clock').text(
	        	currentServerDate().format('D. MMMM YYYY - h : mm : ss A')
        	);
	    }, 1000);
	}

	crInitClock(); // init to sync seconds
	crStartClockNow();
</script>

<script type="text/javascript">
@if (auth()->user()->can('employee_time_clock_show'))
	$('.clock').click(function() {
		$.ajax({
			url: '{{ route('employeetimeclock.show') }}',
			type: 'POST',
	 		success: function (data) {
				// console.log(data);
				var buttonIn = '';
				var buttonOut = '';
				var buttonBreakStart = '';
				var buttonBreakEnd = '';
				var shiftDesc = '';

				if (data.hasShift) {
					shiftDesc = '';

					if (data.in) {
						buttonIn = `<button value="1" class="mb-1 btn btn-info btn-sm empTimeClockLog"> {!! trans('lang.clock_button_in') !!} </button> `; 
					}

					if (data.out) {
						buttonOut = `<button value="2" class="mb-1 btn btn-danger btn-sm empTimeClockLog"> {!! trans('lang.clock_button_out') !!} </button> `;
					}

					if (data.breakStart) {
						buttonBreakStart = `<button value="3" class="mb-1 btn btn-warning btn-sm empTimeClockLog"> {!! trans('lang.clock_button_break_start') !!} </button> `;
					}

					if (data.breakEnd) {
						buttonBreakEnd = `<button value="4" class="mb-1 btn btn-success btn-sm empTimeClockLog"> {!! trans('lang.clock_button_break_end') !!} </button> `;
					}
				}else if (data.hasShift == false) {
					shiftDesc = `{!! trans('lang.clock_no_shift_desc') !!}`;
				}else {
					shiftDesc = `{!! trans('lang.clock_no_employee_attached') !!}`;
				}

				Swal.fire({
				    position: 'top',
				    showConfirmButton: false,
				    width: '300px',
				    html: `<p> {!! trans('lang.clock_title') !!} </p>` + buttonIn + buttonOut + buttonBreakStart + buttonBreakEnd + shiftDesc,
				    didOpen: function (dObj) {
		                $('.empTimeClockLog').on('click',function () {
	               			
	               			$.ajax({
			                   	url: '{{ route('employeetimeclock.loggedTime') }}',
			                   	type: 'POST',
			                   	data: {
			                   		type : $(this).val()
			                   	},
			                   	success: function (data) {
			                   		// console.log(data);
			                   		var type = '{{ trans('lang.clock_noty_success') }}';

			                   		if (data.error) {
			                   			type = '{{ trans('lang.clock_noty_error') }}';
			                   		}

									Swal.fire({
									  // position: 'top',
									  icon: type,
									  title: data.msg,
									  showConfirmButton: false,
									  timer: 1500,
									})

		                   			// Swal.close();
			                   	}// end success
		                   });
		                   
		                });
		            },
		            timer: 10000,
		            timerProgressBar: true,
		            willClose: true
		  		}); // end swal

			},// end success
			statusCode: {
	            419: function() { 
	                window.location.href = '{{ url()->current() }}'; 
	            }
	        },
		});// end ajax
	});// end click event
@endif
</script>
@endpush