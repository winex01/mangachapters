@extends(backpack_view('blank'))

@php
  $defaultBreadcrumbs = [
    trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
    $crud->entity_name_plural => url($crud->route),
    trans('backpack::crud.preview') => false,
  ];

  // if breadcrumbs aren't defined in the CrudController, use the default breadcrumbs
  $breadcrumbs = $breadcrumbs ?? $defaultBreadcrumbs;

  $tabs = collect($crud->columns())->pluck('tab')->unique()->toArray();

@endphp

@section('header')
	<section class="container-fluid d-print-none">
    	<a href="javascript: window.print();" class="btn float-right"><i class="la la-print"></i></a>
		<h2>
	        <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
	        <small>{!! $crud->getSubheading() ?? mb_ucfirst(trans('backpack::crud.preview')).' '.$crud->entity_name !!}.</small>
	        @if ($crud->hasAccess('list'))
	          <small class=""><a href="{{ url($crud->route) }}" class="font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
	        @endif
	    </h2>
    </section>
@endsection

@section('content')
<div class="row">
	<div class="{{ $crud->getShowContentClass() }}">

		<div class="tab-container  mb-2">
		    <div class="nav-tabs-custom " id="form_tabs">
		        <ul class="nav nav-tabs " role="tablist">
		            @foreach ($tabs as $tab)
			            <li role="presentation" class="nav-item">
			                <a href="#tab_{{ $tab }}" aria-controls="tab_{{ $tab }}" role="tab" tab_name="{{ $tab }}" data-toggle="tab" class="nav-link 
			                	@if ($loop->first)
			                		active
			                	@endif
			                ">
			                	@lang('lang.'.$tab)
			                </a>
			            </li>
		            @endforeach
		        </ul>

		        <div class="tab-content p-0 ">
		            @foreach ($tabs as $tab)
		            	<div role="tabpanel" class="tab-pane  
		            		@if ($loop->first)
		            			active
		            		@endif
		            	" id="tab_{{ $tab }}">
			                <div class="row">
			                	<table class="table table-striped mb-0">
							        <tbody>
							        @foreach (collect($crud->columns())->where('tab', $tab) as $column)
							            <tr>
							                <td>
							                    <strong>{!! $column['label'] !!}:</strong>
							                </td>
					                        <td>
												@if (!isset($column['type']))
							                      @include('crud::columns.text')
							                    @else
							                      @if(view()->exists('vendor.backpack.crud.columns.'.$column['type']))
							                        @include('vendor.backpack.crud.columns.'.$column['type'])
							                      @else
							                        @if(view()->exists('crud::columns.'.$column['type']))
							                          @include('crud::columns.'.$column['type'])
							                        @else
							                          @include('crud::columns.text')
							                        @endif
							                      @endif
							                    @endif
					                        </td>
							            </tr>
							        @endforeach
									@if ($crud->buttons()->where('stack', 'line')->count())
										<tr>
											<td><strong>{{ trans('backpack::crud.actions') }}</strong></td>
											<td>
												@php
													$tabLink = \Str::snake($tab);
													$tabLink = str_replace('_', '-', $tab);
													$stack = 'line';
												@endphp

												{{-- @include('crud::inc.button_stack', ['stack' => 'line']) --}}
												@if ($crud->buttons()->where('stack', $stack)->count())
													@foreach ($crud->buttons()->where('stack', $stack) as $button)
														@if ($button->name == 'update')
															@include('crud::buttons.custom_update_with_tab_link', ['tab' => $tabLink])
															@continue;
														@endif
														{!! $button->getHtml($entry ?? null) !!}
													@endforeach
												@endif

											</td>
										</tr>
									@endif
							        </tbody>
								</table>

			                </div>
			            </div>
		            @endforeach
		        </div>
		    </div>
		</div>

	</div>
</div>
@endsection


@section('after_styles')
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
	<script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('packages/backpack/crud/js/show.js') }}"></script>
@endsection
