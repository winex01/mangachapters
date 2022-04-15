@extends('layouts.guest_main')

@section('content')

<main role="main" class="container">
  
  <div class="d-flex align-items-center p-3 my-3 text-white-50 bg-dark rounded shadow-sm">
    <a href="/">
      <img class="mr-3" src="{{ asset('images/logo.svg') }}" alt="" width="50" height="50">
    </a>
    <div class="lh-100">
      <a href="/" style="text-decoration: none;">
        <h6 class="mb-0 text-white lh-100">{{ config('appsettings.app_name') }}</h6>
      </a>
      <small>{!! config('appsettings.app_slogan') !!}</small>
    </div>
  </div>

  

  <div class="my-3 p-3 bg-white rounded shadow-sm">
    <div class="float-right">
      <a class="text-primary" href="/login">Login</a>
      <a class="text-success" href="/register">Register</a>
    </div>

    @yield('guest_blank_content')

  </div>


    @yield('guest_blank_after_content')
    
    <div class="my-3 p-3 bg-white rounded shadow-sm">
      <div class="bd-footer text-muted">
        <div class="container-fluid p-3 p-md-5">
          <div class="row">

            <div class="col-md-2">
              <ul class="bd-footer-links">
                <li><a class="{{ (request()->is('/')) ? 'text-info' : 'text-muted' }}" href="{{ url('/') }}">Home</a></li>
                <li><a class="{{ (request()->is('about-us')) ? 'text-info' : 'text-muted' }}" href="{{ url('about-us') }}">About Us</a></li>
                {{-- <li><a class="{{ (request()->is('contact')) ? 'text-info' : 'text-muted' }}" href="{{ url('contact') }}">Contact</a></li> --}}
                <li><a class="text-muted" href="https://www.facebook.com/ManghwuaHub">Facebook</a></li>
                <li><a class="{{ (request()->is('privacy-policy')) ? 'text-info' : 'text-muted' }}" href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                <li><a class="{{ (request()->is('terms')) ? 'text-info' : 'text-muted' }}" href="{{ url('terms') }}">Terms</a></li>
              </ul>
            </div>

            <div class="col-md-8">
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Aliquam repudiandae delectus atque, tempore et corrupti facilis modi? Laboriosam, eligendi voluptatum. Dolores dolorum perspiciatis rerum quod nobis assumenda quos omnis id.</p>  
              <p>Designed and built with all the love in the world by <a href="https://twitter.com/mdo" target="_blank" rel="noopener">@mdo</a> and <a href="https://twitter.com/fat" target="_blank" rel="noopener">@fat</a>. Maintained by the <a href="https://github.com/orgs/twbs/people">core team</a> with the help of <a href="https://github.com/twbs/bootstrap/graphs/contributors">our contributors</a>.</p>
              <p>Currently v4.1.3. Code licensed <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE" target="_blank" rel="license noopener">MIT</a>, docs <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank" rel="license noopener">CC BY 3.0</a>.</p>
            </div>
            
          </div>
        </div>
      </div>
    </div>

</main>

@endsection