@if ($crud->hasAccess('export') && $crud->get('list.bulkActions'))
	<div class="btn-group dropdown float-right ml-1">
		<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" title="You can check rows to export specific items." data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<i class="la la-download"></i>
			{{ __('Export') }}
		</button>
		<div class="dropdown-menu">
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="xlsx" onclick="bulkEntries(this)">Excel New</a>
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="xls" onclick="bulkEntries(this)">Excel Old</a>
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="csv" onclick="bulkEntries(this)">CSV</a>
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="pdf" onclick="bulkEntries(this)">PDF</a>
			<a href="javascript:void(0)" class="dropdown-item text-sm-left" data-export-type="html" onclick="bulkEntries(this)">Print</a>
			@stack('custom_export_dropdown')
		</div>

		<div class="dropdown ml-1">
			<button class="btn btn-sm btn-secondary dropdown-toggle" title="Export columns" type="button" 
			id="sampleDropdownMenu" data-toggle="dropdown">
				<i class="la la-columns"></i>
				{{ __('Column Export') }}
			</button>

			@php
				// override using dbColumns method at contorller setup method
				if ($crud->dbColumns() != null) {
					$dbColumns = $crud->dbColumns();
				}else {
					$dbColumns = getTableColumns($crud->model->getTable());
				}

				// dd($dbColumns);

				$dbColumns = collect($dbColumns)->chunk(12);
				$dontInclude = config('appsettings.dont_include_in_exports');

			@endphp
			<div class="dropdown-menu multi-column columns-{{ count($dbColumns) }}">
				<div class="row">
					@foreach ($dbColumns as $dbColumn)
						<div class="col-sm-{{ 12 / count($dbColumns) }}">
				            <ul class="multi-column-dropdown">
								@foreach ($dbColumn as $column)
									@php
										if (in_array($column, $dontInclude)) {
											continue;
										}
										$label = str_replace('accessor_', '', $column); // remove prefix accessor
										$label = str_replace('_as_export', '', $label); // remove suffix as_text
										$label = ucwords(str_replace('_', ' ', str_replace('_id', '', $label)));

									@endphp
									<li>
										<a href="javascript:void(0)" class="export-link dropdown-item" data-value="{{ $column }}" tabIndex="-1">
											<input type="checkbox" 
											@if ($crud->checkOnlyCheckbox() != null)
												@if (in_array($column, $crud->checkOnlyCheckbox()))
													checked 
												@endif
											@else
												checked 
											@endif
											/> 
											{{ $label }}
										</a>
									</li>
								@endforeach
				            </ul>
			            </div>
					@endforeach
				</div>
			</div>
		</div>
	</div>

@push('after_scripts')
	@php
		$dbColumns = ($crud->checkOnlyCheckbox()) ?: $dbColumns;
		$dbColumns = collect($dbColumns)->flatten()->toArray();
	@endphp
	<x-export-columns :exportColumns="$dbColumns" ></x-export-columns>
@endpush

@endif

@push('after_scripts')
<script>
	var dataTableColumnHeaders = [];
	$("#crudTable thead tr th").each(function(i){
		var str = $(this).text()
		dataTableColumnHeaders[i] = str.replace(/\s+/g, ' ')
	});

	if (typeof bulkEntries != 'function') {
		function bulkEntries(button) {
			var button = $(button);
			var route = "{{ url($crud->route) }}/export";
			var exportType = button.attr('data-export-type');

			var currentColumnOrder = localStorage.getItem('DataTables_crudTable_'+'{{ $crud->route }}');
			if (currentColumnOrder != null) {
				currentColumnOrder = JSON.parse(currentColumnOrder);
				currentColumnOrder = currentColumnOrder.order;
				if (currentColumnOrder.length != 0 ) {
					currentColumnOrder = {
						'column' : dataTableColumnHeaders[currentColumnOrder[0][0]],
						'orderBy' : currentColumnOrder[0][1]
					};
				}else {
					currentColumnOrder = null;
				}
			}

			// console.log(currentColumnOrder); return;
			// console.log(exportType);
			// console.log(crud.checkedItems); 
			// console.log(exportColumns);
			// console.log(Object.fromEntries(new URLSearchParams(location.search)));
			// return;

			if (typeof exportColumns === 'undefined' || exportColumns.length == 0)
			{
			  	new Noty({
			      type: "warning",
			      text: "<strong>{!! trans('backpack::crud.export_no_entries_selected_title') !!}</strong><br>{!! trans('backpack::crud.export_no_entries_selected_message') !!}"
			    }).show();

			  	return;
			}

			swalLoader();

			// submit an AJAX delete call
			$.ajax({
				url: route,
				type: 'post',
				data: { 
					entries			 	: crud.checkedItems, 
					model 			  	: "{{ $crud->model->model }}", 
					exportColumns 	  	: exportColumns,  
					exportType 			: exportType,  
					filters			 	: filters(), 
					currentColumnOrder 	: currentColumnOrder
				},
				success: function(result) {
					// console.log(result);
					if (result) {
						if (result.exportType == 'pdf') {
							var link = document.createElement('a');
							link.href = result.link;
							link.download = result.fileName;
							link.dispatchEvent(new MouseEvent('click'));
						}else if (result.exportType == 'html') {
							var theWindow = window.open(result.link),
							    theScript;
							function injectThis() {
							    window.print(); 
								setTimeout(window.close, 0);

							}
							// Self executing function
							theScript = '(' + injectThis.toString() + '());';
							theWindow.onload = function () {
							    this.eval(theScript);
							};
						}else {
							window.location.href = result.link;
						}
					  	// console.clear(); // NOTE:: clear

					  	swalSuccess();

					  	// delete temporary file
					  	$.ajax({
					  		url: "{{ url($crud->route) }}/delete-file",
					  		type: 'post',
					  		data: {
					  			fileName: result.fileName
					  		},
					  	});
					  	
						// if print/html
						if (result.exportType == 'html') {
							// Show a success notification bubble
							new Noty({
								type: "info",
								text: "{{ trans('backpack::crud.export_html_preview_warning') }}",
								timeout: 100
							}).show();
						}

					} else {
					  	// Show a warning notification bubble
						new Noty({
							type: "warning",
							text: "<strong>{!! trans('backpack::crud.export_error_title') !!}</strong><br>{!! trans('backpack::crud.export_error_message') !!}"
						}).show();

						swalError();
					}

				  	// crud.checkedItems = [];
				  	// crud.table.ajax.reload();
				},
				error: function(result) {
					// Show an alert with the result
					new Noty({
						type: "warning",
						text: "<strong>{!! trans('backpack::crud.export_error_title') !!}</strong><br>{!! trans('backpack::crud.export_error_message') !!}"
					}).show();

					swalError();
				}
			});
		}
	}

	function filters() {
		return Object.fromEntries(new URLSearchParams(location.search));
	}
</script>

@stack('custom_export_js')

@endpush

@push('after_styles')
	{{-- https://codepen.io/dustlilac/pen/Qwpxbp --}}
	{{-- dropdown checkbox column --}}
	<style type="text/css">
		.dropdown-menu {
			min-width: 200px;
		}
		.dropdown-menu.columns-2 {
			min-width: 400px;
		}
		.dropdown-menu.columns-3 {
			min-width: 600px;
		}
		.dropdown-menu li a {
			padding: 5px 15px;
			font-weight: 300;
		}
		.multi-column-dropdown {
			list-style: none;
		  margin: 0px;
		  padding: 0px;
		}
		.multi-column-dropdown li a {
			display: block;
			clear: both;
			line-height: 1.428571429;
			color: #333;
			white-space: normal;
		}
		.multi-column-dropdown li a:hover {
			text-decoration: none;
			color: #262626;
			background-color: #999;
		}
		 
		@media (max-width: 767px) {
			.dropdown-menu.multi-column {
				min-width: 240px !important;
				overflow-x: hidden;
			}
		}
	</style>
@endpush