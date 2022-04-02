<!-- html5 time input -->
@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')
    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-prepend"><span class="input-group-text">{!! $field['prefix'] !!}</span></div> @endif

        <input
            type="text"
            name="{{ $field['name'] }}"
            value="{{ old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '' }}"
            data-init-function="bpFieldInitDateTimePickerElement"
            @include('crud::fields.inc.attributes')
            >
        
        @if(isset($field['suffix'])) <div class="input-group-append"><span class="input-group-text">{!! $field['suffix'] !!}</span></div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')


{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->fieldTypeNotLoaded($field))
    @php
        $crud->markFieldTypeAsLoaded($field);
    @endphp

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
    <link rel="stylesheet" href="{{ asset('packages/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('packages/pc-bootstrap4-datetimepicker/build/css/bootstrap-datetimepicker.min.css') }}">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
    <script type="text/javascript" src="{{ asset('packages/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('packages/pc-bootstrap4-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
    
    <script type="text/javascript">
        function bpFieldInitDateTimePickerElement(element) {
            $(element).datetimepicker({
                format: 'HH:mm',
                icons: {
                    time: 'la la-clock text-bold',
                    date: 'la la-calendar',
                    up: 'la la-arrow-up',
                    down: 'la la-arrow-down',
                }
            });
        }
    </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}