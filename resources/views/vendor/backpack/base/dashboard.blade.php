@extends(backpack_view('blank'))

@section('content')
    @php
        $hasNotifications = false;
    @endphp
    @forelse (auth()->user()->unreadNotifications
        ->where('type', 'App\Notifications\NewChapterNotification') as $notification)
        
        @php
            $hasNotifications = true;
            $data = $notification->data;
            $chapter = modelInstance($data['model'])->with('manga')->find($data['id']);

            // dump($chapter);
        @endphp

        <div 
            class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark" 
            role="alert" 
            data-route="{{ route('dashboard.markAsReadNotification', $notification->id) }}"
        >
            
            <img style="height: 50px; width:40px;" src="{{ $chapter->manga->photo }}" class="rounded" alt="...">
            <span class="ml-1">{{ $chapter->manga->name }}!</span> 

            @php
                $label = "Chapter $chapter->chapter is out $chapter->release."
            @endphp

            {!! anchorNewTab($chapter->url, $label) !!}
            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
    @empty
        <p>No notification(s).</p>
    @endforelse

    @if ($hasNotifications)
        <a href="javascript:void(0)" id="clear-all-notifications">Clear all notification.</a>
    @endif
    
@endsection

@push('after_scripts')
<script>
    $('.chapter-alert').on('closed.bs.alert', function () {
       
        $.ajax({
            type: "post",
            url: $(this).attr('data-route'),
            success: function (response) {
                // console.log(response);

                // Show a success notification bubble
                new Noty({
                    type: "success",
                    text: "{!! '<strong>'.trans('backpack::crud.delete_confirmation_title').'</strong><br>'.trans('backpack::crud.delete_confirmation_message') !!}"
                }).show();
            }
        });
    });
    
    $('#clear-all-notifications').click(function (e) { 
        e.preventDefault();
        
        $.ajax({
            type: "post",
            url: "{{ route('dashboard.clearAllNotification') }}",
            success: function (response) {
                // console.log(response);
                $('.chapter-alert').hide();
                
                // Show a success notification bubble
                new Noty({
                    type: "success",
                    text: "{!! '<strong>'.trans('backpack::crud.delete_confirmation_title').'</strong><br>'.trans('backpack::crud.delete_confirmation_message') !!}"
                }).show();
            }
        });        
    });
</script>
@endpush