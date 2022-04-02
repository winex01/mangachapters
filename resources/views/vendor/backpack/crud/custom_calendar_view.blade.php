
@extends(backpack_view('blank'))

@php
  // dd($calendar);
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('lang.calendar') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

  $backButtonUrl = url($crud->route);
  $backButtonDesc = trans('backpack::crud.back_to_all') .'<span> '.$crud->entity_name_plural.' </span>';

  // modify back button url and desc
  if ($backButton) {
    $backButtonUrl = $backButton['backButtonUrl'];
    $backButtonDesc = $backButton['backButtonDesc'];
  }
@endphp

@section('header')
  <section class="container-fluid d-print-none">
      @can($crud->model->getTable().'_export')
        <a href="javascript:void(0)" onclick="printData()" class="btn btn-sm btn-success float-right"><i class="la la-print"></i> Print </a>
      @endcan

      <h2>
        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('lang.calendar')).' '.$crud->entity_name !!}.</small>
        @if ($crud->hasAccess('list'))
          <small class=""><a href="{{ $backButtonUrl }}" class="font-sm"><i class="la la-angle-double-left"></i> {!! $backButtonDesc !!}</a></small>
        @endif
      </h2>
    </section>
@endsection

@section('content')
<div class="row">
  <div id="print-div" class="{{ $contentClass }}">

  <!-- Default box -->
    <div class="">
      @if ($crud->model->translationEnabled())
      <div class="row">
        <div class="col-md-12 mb-2">
        <!-- Change translation button group -->
        <div class="btn-group float-right">
          <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            {{trans('backpack::crud.language')}}: {{ $crud->model->getAvailableLocales()[request()->input('locale')?request()->input('locale'):App::getLocale()] }} &nbsp; <span class="caret"></span>
          </button>
          <ul class="dropdown-menu">
            @foreach ($crud->model->getAvailableLocales() as $key => $locale)
              <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/show') }}?locale={{ $key }}">{{ $locale }}</a>
            @endforeach
          </ul>
        </div>
      </div>
      </div>
      @else
      @endif
      <div class="card">
        <div class="card-header with-border">

            <div class="form-group">
              <select class="form-control col-md-6" id="employee_calendar">
                @foreach ($employees as $empId => $empName)
                  <option value="{{ $empId }}"
                  @if ($id == $empId)
                    selected 
                  @endif
                  >{{ $empName }}</option>
                @endforeach
              </select>
            </div>


            @if ($calendar != null) 
              <div class="row">
                  <ul class="legend">
                      <li><span class="legend-info"></span> Employee Shift Schedule</li>
                      <li><span class="legend-success"></span> Change Shift Schedule</li>
                      <li><span class="legend-primary"></span> Regular Holiday</li>
                      <li><span class="legend-warning"></span> Special Holiday</li>
                      <li><span class="legend-secondary"></span> Double Holiday</li>
                  </ul>
              </div>
            
            @if (isset($descriptions))
                <div class="row">
                 <ul>
                   @foreach ($descriptions as $desc)
                      <li><span class="label label-default">{{ $desc }}</span></li>
                   @endforeach
                 </ul>
                </div>
            @endif

              {!! $calendar->calendar() !!}
            @else
              {{ trans('lang.no_entries') }}
            @endif
        </div>
      </div>
    </div><!-- /.box -->

  </div>
</div>

<!-- modal lists -->
@foreach ($modalLists as $modal)
  {!! $modal !!}
@endforeach

@endsection


@section('after_styles')
  <link href="{{ asset('packages/fullcalendar/3.10.2/fullcalendar.min.css') }}" rel="stylesheet" type="text/css" />

  <!-- include select2 css-->
  <link href="{{ asset('packages/select2/dist/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('packages/select2-bootstrap-theme/dist/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />

  <style type="text/css">
    {{-- fix or add line for event title if its too long. --}}
    .fc-day-grid-event > .fc-content { 
      white-space: unset !important; 
    }

    /* basic positioning */
    .legend { list-style: none; }
    .legend li { float: left; margin-right: 10px; }
    .legend span { border: 1px solid #ccc; float: left; width: 16px; height: 16px; margin: 2px; }
    /* colors */
    .legend .legend-info { background-color: {{ config('appsettings.legend_info')  }}; }
    .legend .legend-success { background-color: {{ config('appsettings.legend_success')  }}; }
    .legend .legend-primary { background-color: {{ config('appsettings.legend_primary')  }}; }
    .legend .legend-warning { background-color: {{ config('appsettings.legend_warning')  }}; }
    .legend .legend-secondary { background-color: {{ config('appsettings.legend_secondary')  }}; }


    /*fix swal select2*/
    .select2-container {
      z-index: 9999;
    }
  </style>
@endsection

@section('after_scripts')
  <script src="{{ asset('packages/fullcalendar/2.2.7/moment.min.js') }}"></script>
  <script src="{{ asset('packages/fullcalendar/3.10.2/fullcalendar.min.js') }}"></script>

  <!-- include select2 js-->
  <script src="{{ asset('packages/select2/dist/js/select2.full.min.js') }}"></script>
  @if (app()->getLocale() !== 'en')
  <script src="{{ asset('packages/select2/dist/js/i18n/' . app()->getLocale() . '.js') }}"></script>
  @endif
  
  @if ($calendar != null) 
    {!! $calendar->script() !!}
  @endif

  <script type="text/javascript">
    $(document).ready(function() {
        $('#employee_calendar').select2();
    });

    $('#employee_calendar').change(function() {
      location.href = "{{ url($crud->route) }}/"+this.value+"/calendar";
    }); 
  </script>

  @include('crud::inc.custom_printData')
@endsection