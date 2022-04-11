<div class="media text-muted pt-3 col-md-4">
    <img style="height: 50px; width:40px;" src="{{ $chapter->manga->photo }}" class="rounded" alt="...">
    <div class="flexbox ml-2 media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        
        <strong class="d-block text-gray-dark">{{ $chapter->manga->title }}</strong>
    
        {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
            'chapter' => $chapter->chapter, 
            'release' => $chapter->release, 
        ]) ) !!}

        <br>

        @auth
            <a href="javascript:void(0)" class="chapter-alert text-muted">{{ trans('lang.chapter_mark_as_read') }}</a>
        @else
            <a href="{{ route('manga.index') }}" class="text-muted">
                <i class="las la-bookmark"></i>
                {{ trans('lang.chapter_bookmark') }}
            </a>
        @endauth

    </div>
</div>