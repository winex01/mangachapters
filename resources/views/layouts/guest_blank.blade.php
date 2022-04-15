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
          <ul class="bd-footer-links">
            <li><a href="https://github.com/twbs/bootstrap">Support Us</a></li>
            <li><a href="https://twitter.com/getbootstrap">Facebook</a></li>
            <li><a href="/docs/4.1/about/overview/">About</a></li>
            <li><a href="/docs/4.1/about/overview/">Contact</a></li>
          </ul>
          <p>Designed and built with all the love in the world by <a href="https://twitter.com/mdo" target="_blank" rel="noopener">@mdo</a> and <a href="https://twitter.com/fat" target="_blank" rel="noopener">@fat</a>. Maintained by the <a href="https://github.com/orgs/twbs/people">core team</a> with the help of <a href="https://github.com/twbs/bootstrap/graphs/contributors">our contributors</a>.</p>
          <p>Currently v4.1.3. Code licensed <a href="https://github.com/twbs/bootstrap/blob/main/LICENSE" target="_blank" rel="license noopener">MIT</a>, docs <a href="https://creativecommons.org/licenses/by/3.0/" target="_blank" rel="license noopener">CC BY 3.0</a>.</p>
        </div>
      </div>
    </div>

</main>

@endsection