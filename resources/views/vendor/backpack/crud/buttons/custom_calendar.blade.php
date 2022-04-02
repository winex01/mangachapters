@if ($crud->hasAccess('calendar'))
	@if (!$crud->model->translationEnabled())

	<!-- Single button -->
	<a href="{{ url($crud->route.'/'.$entry->employee_id.'/calendar') }}" class="btn btn-sm btn-link" data-toggle="tooltip" title="{{ trans('lang.calendar') }}"><i class="las la-business-time"></i></a>

	@else

	<!-- button group -->
	<div class="btn-group">
	  <a href="{{ url($crud->route.'/'.$entry->employee_id.'/calendar') }}" class="btn btn-sm btn-link pr-0" data-toggle="tooltip" title="{{ trans('lang.calendar') }}"><i class="las la-business-time"></i></a>
	  <a class="btn btn-sm btn-link dropdown-toggle text-primary pl-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
	    <span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu dropdown-menu-right">
  	    <li class="dropdown-header">{{ trans('lang.calendar') }}:</li>
	  	@foreach ($crud->model->getAvailableLocales() as $key => $locale)
		  	<a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->employee_id.'/calendar') }}?locale={{ $key }}">{{ $locale }}</a>
	  	@endforeach
	  </ul>
	</div>

	@endif
@endif