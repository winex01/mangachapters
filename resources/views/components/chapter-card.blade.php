<div class="media text-muted pt-3 mb-n2 col-md-4 alert alert-dismissible fade show" role="alert">
    <img style="height: 50px; width:40px;" src="{{ $chapter->manga->photo }}" class="rounded" alt="...">
    <div class="flexbox ml-2 media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        
        <strong class="d-block text-gray-dark">{{ $chapter->manga->title }}</strong>
    
        {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
            'chapter' => $chapter->chapter, 
            'release' => $chapter->release, 
        ]) ) !!}

        @yield('chapter_card')
    </div>
</div>

{{-- 
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div> --}}