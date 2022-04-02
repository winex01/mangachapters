@if ($crud->hasAccess('revise') && count($entry->revisionHistory))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/revise') }}" class="btn btn-sm btn-link" data-toggle="tooltip" title="{{ trans('revise-operation::revise.revisions') }}"><i class="la la-history"></i></a>
@endif
