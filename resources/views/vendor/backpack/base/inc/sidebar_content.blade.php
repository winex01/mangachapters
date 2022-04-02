<x-after-scripts></x-after-scripts>

{{-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 --}}
@php
	$menus = \App\Models\Menu::whereNull('parent_id')->orderBy('lft')->get();
@endphp

@foreach ($menus as $menu)
	@if ($menu->url == null && $menu->icon == null)
		{{-- show as label or title --}}
		@can($menu->permission)
			<li class="nav-title">
				{{ $menu->label }} 
			</li>
		@endcan
	@elseif ($menu->url != null) 
		{{-- normal menu --}}
		@can($menu->permission)
			<li class="nav-item">
				<a class="nav-link" href="{{ backpack_url($menu->url) }}">
					{!! $menu->icon !!} 
					{{ $menu->label }} 
				</a>
			</li>
		@endcan
	@else
		@php
			$subMenus = \App\Models\Menu::where('parent_id', $menu->id)->orderBy('lft')->get();
			$subMenusPermissions = $subMenus->pluck('permission')->toArray();

			// dump($subMenusPermissions);
		@endphp
		
		{{-- sub menu --}}
		@foreach ($subMenus as $subMenu)
			@if ($loop->first && auth()->user()->canAny($subMenusPermissions))
					<li class="nav-item nav-dropdown">
						<a class="nav-link nav-dropdown-toggle" href="#">
							{!! $menu->icon !!} 
							{{ $menu->label }}
						</a>
						<ul class="nav-dropdown-items">
			@endif
							@can($subMenu->permission)
							 		@if ($subMenu->url == '#') {{-- if url == # then the link is fully define in icon property --}}
							 			{!! $subMenu->icon !!}
							 		@else
							 			<li class="nav-item">
									 		<a class="nav-link" href="{{ backpack_url($subMenu->url) }}" 
								 				title="{{ (strlen($subMenu->label) > 15) ? $subMenu->label: null }}
								 			">
									 			{!! $subMenu->icon !!} 
												{{ str_limit($subMenu->label, 15, '...') }} 
									 		</a>
						 				</li>
							 		@endif
							@endcan

			@if ($loop->last && auth()->user()->canAny($subMenusPermissions))
						</ul>
					</li>
			@endif
		@endforeach
	@endif
@endforeach
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('chapter') }}'><i class='nav-icon la la-question'></i> Chapters</a></li>