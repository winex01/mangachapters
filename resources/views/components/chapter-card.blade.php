<div class="chapter-card media text-muted pt-3 mb-n2 col-md-4 alert alert-dismissible fade show" role="alert">
    <img style="height: 50px; width:40px;" src="{{ $chapter->manga->photo }}" class="rounded" alt="...">
    <div class="flexbox ml-2 media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
        
        <strong class="d-block text-gray-dark">{{ $chapter->manga->title }}</strong>
    
        {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
            'chapter' => $chapter->chapter, 
            'release' => $chapter->release, 
        ]) ) !!}

        @auth
            <a 
                href="javascript:void(0)" 
                class="mark-as-read text-muted"
                data-dismiss="alert"
                data-id="{{ $notification->id }}">
                    {{ trans('lang.chapter_mark_as_read') }}
            </a>
        @else
            <a href="{{ route('manga.index') }}" class="text-muted">
                {{ trans('lang.chapter_bookmark') }}
                <i class="las la-bookmark" title="{{ trans('lang.chapter_bookmark') }}"></i>
            </a>
        @endauth

    </div>
</div>
