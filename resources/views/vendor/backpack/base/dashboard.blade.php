@extends(backpack_view('blank'))

@section('content')

    @if (!auth()->user()->hasVerifiedEmail())
        <div class="card">
            <div class="card-header">{{ __('Verify Your Email Address (Optional)') }}</div>
            
            <div class="card-body">
                @if (session('message'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
                @endif
                
                {{ __('Before proceeding, please check your email for a verification link.') }}
                {{ __('If you did not receive the email') }},
                <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                </form>
            </div>
        </div>
    @endif

    @php
        $firstLoop = true;
        $tempValue = null;
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
                $notification->markAsRead();
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
        @php
            $tempValue = 'No notification(s).';
        @endphp
    @endforelse

    <p id="temp">{{ $tempValue }}</p>

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

                $('#temp').text('No notification(s).')

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