@if (!request()->ajax())
 	window.location.href = "{{ url($crud->route) }}";
@endif