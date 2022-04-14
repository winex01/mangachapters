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
      <small>{!! trans('lang.slogan') !!}</small>
    </div>
  </div>

  

  {{-- BEGIN CHAPTERS --}}
  <div class="my-3 p-3 bg-white rounded shadow-sm">
    <div class="float-right">
      <a class="text-primary" href="/login">Login</a>
      <a class="text-success" href="/register">Register</a>
    </div>

    <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('lang.chapter_recent_chapters') }}</h6>

    @foreach ($chapters->chunk(3) as $chunks)

      <div class="row">

        @foreach ($chunks as $chapter)

          <x-chapter-card :chapter="$chapter"></x-chapter>
          
        @endforeach
        
      </div>
    @endforeach

    <small class="d-block text-right mt-3">
      {{ $chapters->links() }}
    </small>

  </div>
  {{-- END chapters --}}


  <div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Available mangas') }}</h6>


    @foreach ($mangas->chunk(3) as $chunks)
      <div class="row">

        @foreach ($chunks as $manga)
          
        <div class="chapter-card media text-muted pt-3 mb-n2 col-md-4">
          <img style="height: 55px; width:40px;" src="{{ $manga->photo }}" class="mt-1 rounded" alt="...">
          <div class="flexbox ml-2 media-body small border-bottom border-gray">
              
              <strong class="d-block text-gray-dark" data-mng-id="{{ $manga->id }}">{{ $manga->title }}</strong>
            
              @php
                  $count = 0;
              @endphp
              @foreach ($manga->chapters as $chapter)
                  @php
                      $count++;
                  @endphp
                  {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
                    'chapter' => $chapter->chapter, 
                    'release' => $chapter->release, 
                  ]) ) !!}
                  <br>
              @endforeach

              @if ($count < 2)
                  <br>                  
              @endif
      
          </div>
        </div>
      

        @endforeach
        
      </div>
    @endforeach

    <small class="d-block text-right mt-3">
      <a href="/manga">{{ __('See all mangas') }}</a>
    </small>
  </div>

  



</main>

@endsection


@push('after_scripts')
@endpush