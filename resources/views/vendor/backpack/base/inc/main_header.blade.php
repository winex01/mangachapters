<header class="{{ config('backpack.base.header_class') }}">
    <!-- Logo -->
    
    @if (!backpack_auth()->guest())
    <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto ml-3" type="button" data-toggle="sidebar-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
      <span class="navbar-toggler-icon"></span>
    </button>
    @endif

    <a class="navbar-brand" href="{{ url(config('backpack.base.home_link')) }}" title="{{ config('backpack.base.project_name') }}">
      {!! config('backpack.base.project_logo') !!}
    </a>

    @if (!backpack_auth()->guest())
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show" aria-label="{{ trans('backpack::base.toggle_navigation')}}">
      <span class="navbar-toggler-icon"></span>
    </button>
    @endif
  
    @include(backpack_view('inc.menu'))
  </header>
  