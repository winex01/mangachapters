@extends(backpack_view('blank'))

@section('content')

    @php
        $firstLoop = true;
    @endphp
    
    @forelse (auth()->user()->unreadNotifications
        ->where('type', 'App\Notifications\NewChapterNotification') as $notification)
        
        @if ($firstLoop)
            <a class="btn btn-danger btn-sm mb-3" href="javascript:void(0)" id="clear-all-notifications">Clear all notification(s).</a>
        @endif

        @php
            $firstLoop = false;
            $data = $notification->data;
            $chapter = modelInstance($data['model'])->with('manga')->find($data['id']);

            // if no data is find then perhaps i deleted the notification in database, so escape this loop
            if (!$chapter) {
                continue;
            }
        @endphp

        <div 
            class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark" 
            role="alert" 
            data-id="{{ $notification->id }}"
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

@endsection

@push('after_scripts')
<script>
    $('.chapter-alert').on('closed.bs.alert', function () {
       
        $.ajax({
            type: "post",
            url: "{{ route('dashboard.markAsReadNotification') }}",
            data: {
                ids : $(this).attr('data-id')
            },
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
        
        var dataArray = $('.chapter-alert').map(function(){
            return $(this).data('id');
        }).get();


        $.ajax({
            type: "post",
            url: "{{ route('dashboard.markAsReadNotification') }}",
            data: {
                ids : dataArray
            },
            success: function (response) {
                // console.log(response);
                $('.chapter-alert').hide();
                
                $('#clear-all-notifications').hide();

                $('.container-fluid').html('<p>No notification(s).</p>')

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