@if ($crud->hasAccess('revisions') && count($entry->revisionHistory))
    <a href="{{ url($crud->route.'/'.$entry->getKey().'/revisions') }}" class="btn btn-xs btn-default action-btn"><i class="fa fa-history" title="Review Revisions"></i> 
    {{-- {{ trans('backpack::crud.revisions') }} --}}
</a>
@endif
