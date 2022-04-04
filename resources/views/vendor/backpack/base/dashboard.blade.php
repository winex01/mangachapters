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

        <div class="myAlert alert alert-secondary alert-dismissible fade show text-dark" role="alert">
            <span>{{ $chapter->manga->name }}!</span> 

            @php
                $label = "Chapter $chapter->chapter is out $chapter->release."
            @endphp

            {!! anchorNewTab($chapter->url, $label) !!}
            
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        
    @empty
        <p>No notifications.</p>
    @endforelse

    @if ($hasNotifications)
        <a href="#">Clear all notification.</a>
    @endif
    
@endsection

@push('after_scripts')
<script>
    $('.myAlert').on('closed.bs.alert', function () {
        alert('winex'); 
        // TODO:: ajax request to mark as read noti
    });
</script>
@endpush