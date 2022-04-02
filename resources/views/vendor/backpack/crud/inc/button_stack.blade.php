@if ($crud->buttons()->where('stack', $stack)->count())
	@foreach ($crud->buttons()->where('stack', $stack) as $button)
	  {!! $button->getHtml($entry ?? null) !!}
		
		@if($button->stack == 'bottom')
			{{-- Note:: added this invisible anchor to move or fix position of pagination, i added it here bec. i dont want to modify the list.blade file --}}
			<a href="javascript:void(0)" style="pointer-events: none;">&nbsp;</a>
		@endif

	@endforeach
@endif

