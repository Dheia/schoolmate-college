@if ($crud->hasAccess('update'))
	<li>
		<a href="{{ str_replace('search/','',Request::url().'/'.$entry->getKey()) }}/print"  class="text-sm" title="Print">
			<i class="fa fa-print"></i> Print
		</a>
	</li>
@endif