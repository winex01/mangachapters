@extends('layouts.guest_blank')

@section('guest_blank_content')

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

@endsection


@section('guest_blank_after_content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
  <h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Random mangas') }}</h6>

    @foreach ($mangas->chunk(3) as $chunks)
      <div class="row">

          @foreach ($chunks as $manga)
            
              <div class="chapter-card media text-muted pt-3 mb-n2 col-md-4">
                <img style="height: 55px; width:40px;" src="{{ $manga->photo }}" class="mt-1 rounded" alt="...">
                <div class="flexbox ml-2 media-body small border-bottom border-gray">
                    
                    <a class="d-block text-muted font-weight-bold" href="{{ url('manghwua/'.$manga->id) }}" >
                      {!! $manga->titleInHtml !!}
                    </a>
                  
                    @php
                        $count = 0;
                    @endphp
                    @foreach ($manga->chapters as $chapter)
                        @php
                            $count++;
                        @endphp
                        {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
                          // 'chapter' => $chapter->chapter, 
                          'chapter' => str_limit($chapter->chapter, 5),
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


    
@endsection