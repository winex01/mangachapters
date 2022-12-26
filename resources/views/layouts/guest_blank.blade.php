@extends('layouts.guest_main')

@section('content')

<main role="main" class="container">
  
  <nav class="container navbar navbar-expand-lg navbar-dark bg-dark rounded shadow-sm">
    <a class="navbar-brand" href="/">
      <img class="mr-n1 ml-n2" src="{{ asset('images/winexhub.png') }}" alt="" width="150" height="73">
      {{-- {{ config('appsettings.app_name') }} --}}
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <span class="navbar-text">
      <small class="">
        {!! config('appsettings.app_slogan') !!}
      </small>
    </span>

    <div class="collapse navbar-collapse" id="navbarText">

      <ul class="navbar-nav ml-auto">
        <li class="nav-item {{ (request()->is('/')) ? 'active' : '' }}">
          <a class="nav-link" href="/">Home</a>
        </li>

        <li class="nav-item {{ (request()->is('about-us')) ? 'active' : '' }}">
          <a class="nav-link" href="/about-us">About</a>
        </li>

        <li class="nav-item {{ (request()->is('terms')) ? 'active' : '' }}">
          <a class="nav-link" href="/terms">Terms</a>
        </li>

        <li class="nav-item {{ (request()->is('contact')) ? 'active' : '' }}">
          <a class="nav-link" href="/contact">Contact</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ config('appsettings.discord_link') }}">Discord</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="{{ config('appsettings.social_media') }}">Social</a>
        </li>

      </ul>

    </div>
  </nav>


  <div class="my-3 p-3 bg-white rounded shadow-sm">
    @unless (auth()->check())
      <div class="float-right">
        <a class="text-primary" href="/login">Login</a>
        <a class="text-success" href="/register">Register</a>
      </div>
    @endunless

    @yield('guest_blank_content')

  </div>


    @yield('guest_blank_after_content')

</main>

@endsection