<div class="chapter-card media text-muted pt-3 mb-n2 col-md-4 alert alert-dismissible fade show" role="alert">
    <img style="height: 55px; width:40px;" 
        src="{{ mangaPhoto($chapter->manga->photo) }}" 
        class="mt-1 rounded" 
        alt="..."
    >
    <div class="flexbox ml-2 media-body small border-bottom border-gray">
        
        @auth
            <a class="d-block text-muted font-weight-bold" href="{{ backpack_url('manga/'.$chapter->manga->id.'/show') }}" >
                {!! $chapter->manga->titleInHtml !!}
            </a>
        @else
            <a class="d-block text-muted font-weight-bold" href="{{ url('manghwua/'.$chapter->manga->slug) }}" >
                {!! $chapter->manga->titleInHtml !!}
            </a>
        @endauth

    
        {!! anchorNewTab($chapter->url, trans('lang.chapter_description', [
            // 'chapter' => $chapter->chapter, 
            'chapter' => str_limit($chapter->chapter, 5), 
            'release' => $chapter->release, 
        ]) ) !!}

        <br>

        @if (isset($notification))
            <a 
                href="javascript:void(0)" 
                class="mark-as-read text-muted"
                data-dismiss="alert"
                data-id="{{ $notification->id }}">
                    {{ trans('lang.chapter_mark_as_read') }}
            </a>
        @else
            <a href="{{ route('manga.index') }}" class="text-muted">
                <i class="las la-bookmark" title="{{ trans('lang.chapter_bookmark') }}"></i>
                {{ trans('lang.chapter_bookmark') }}
            </a>
        @endauth

    </div>
</div>
