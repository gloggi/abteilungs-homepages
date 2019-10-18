@if ($crud->hasAccess('create'))
	<a href="{{ url($crud->route.'/create') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('crud.add-entity', ['entity' => trans($crud->entity_name)]) }}</span></a>
@endif
