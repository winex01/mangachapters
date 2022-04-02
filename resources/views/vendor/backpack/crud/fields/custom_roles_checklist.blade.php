<!-- checklist -->
@php
  $model = new $field['model'];
  $key_attribute = $model->getKeyName();
  $identifiable_attribute = $field['attribute'];

  // calculate the checklist options
  if (!isset($field['options'])) {
      $field['options'] = $field['model']::all()->pluck($identifiable_attribute, $key_attribute)->toArray();
  } else {
      $field['options'] = call_user_func($field['options'], $field['model']::query());
  }

  // calculate the value of the hidden input
  $field['value'] = old(square_brackets_to_dots($field['name'])) ?? $field['value'] ?? $field['default'] ?? '';
  if ($field['value'] instanceof Illuminate\Database\Eloquent\Collection) {
    $field['value'] = $field['value']->pluck($key_attribute)->toArray();
  }

  // define the init-function on the wrapper
  $field['wrapper']['data-init-function'] =  $field['wrapper']['data-init-function'] ?? 'bpFieldInitChecklist';

  $roles = config('backpack.permissionmanager.models.role')::
            orderBy('name', 'asc')
            ->pluck('name')->toArray();

@endphp

@include('crud::fields.inc.wrapper_start')
    <input type="hidden" value="@json($field['value'])" name="{{ $field['name'] }}">

    @foreach ($roles as $role)
      @php
        $permissions = collect($field['options'])->filter(function ($item) use ($role) {
            // return false !== stristr($item, $role);
            return startsWith($item, $role);
        })->toArray();

        if (empty($permissions)) {
          continue;
        }
      @endphp

      <hr>

      <div class="row">
          <div class="col-sm-12">
              <label class="">{{ ucwords(str_replace('_', ' ', $role)) }}</label>
          </div>
      </div>

      <div class="row">
          @foreach ($permissions as $key => $option)
              <div class="col-sm-{{ 12 / $field['number_columns'] }}">
                  <div class="checkbox">
                    <label class="font-weight-normal">
                      <input type="checkbox" value="{{ $key }}"> 
                        {{-- {{ $option }} --}}
                        <x-change-roles-and-permissions-name filter="{{ $role }}" tempName="{{ $option }}">
                        </x-change-roles-and-permissions-name>
                    </label>
                  </div>
              </div>
          @endforeach
      </div>

    @endforeach {{-- end foreach roles --}}

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
    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script>
            function bpFieldInitChecklist(element) {
                var hidden_input = element.find('input[type=hidden]');
                var selected_options = JSON.parse(hidden_input.val() || '[]');
                var checkboxes = element.find('input[type=checkbox]');
                var container = element.find('.row');

                // set the default checked/unchecked states on checklist options
                checkboxes.each(function(key, option) {
                  var id = parseInt($(this).val());

                  if (selected_options.includes(id)) {
                    $(this).prop('checked', 'checked');
                  } else {
                    $(this).prop('checked', false);
                  }
                });

                // when a checkbox is clicked
                // set the correct value on the hidden input
                checkboxes.click(function() {
                  var newValue = [];

                  checkboxes.each(function() {
                    if ($(this).is(':checked')) {
                      var id = parseInt($(this).val());
                      newValue.push(id);
                    }
                  });

                  hidden_input.val(JSON.stringify(newValue));

                });
            }
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}
{{-- ########################################## --}}