{{-- hide all buttons in softDeleted or when trashed filter is active --}}
@if ($entry->deleted_at == null) 
@php
    $permissions = authUserPermissions($crud->model->getTable());
    $crud->denyAccess($permissions);

    // show or allow access only if meet condition here
    if ($entry->status == 1 || $entry->status == 2) { 
        foreach ([
            'status',
            'show',
            'revise',
        ] as $button) {
            if (in_array($button, $permissions)) {
                $crud->allowAccess($button);
            }
        }
    }else { 
        $crud->allowAccess($permissions);
    } 

@endphp

    {{-- NOTE:: dont forget to add status button business logic --}}
    @include('crud::buttons.custom_status')
@endif