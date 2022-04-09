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
    
    @forelse (auth()->user()->unreadNotifications  as $notification)
        
        @if ($firstLoop)
            <a class="btn btn-danger btn-sm mb-3" href="javascript:void(0)" id="clear-all-notifications">Clear all notification(s).</a>
        @endif

        @php
            $firstLoop = false;
            $data = $notification->data;
            $model = null;
            $type = null;

            if ($notification->type == 'App\Notifications\NewChapterNotification') {
                $model = modelInstance($data['model'])->with('manga')->find($data['id']);
                $type = 'newChapter';
                
            }elseif ($notification->type == 'App\Notifications\NewUserNotification') {
                $model = modelInstance($data['model'])->find($data['id']);
                $type = 'newUser';
                
            }else {
                $type = 'generalNotification';
                $model = true; // assign model to true so it wont markAsRead at the bottom
            }

            // if no data is find then perhaps i deleted the notification in database, so escape this loop
            if (!$model) {
                $notification->markAsRead();
                continue;
            }
        @endphp

        @if ($type == 'newChapter')

            <div 
                class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark" 
                role="alert" 
                data-id="{{ $notification->id }}"
            >
            
                <img style="height: 50px; width:40px;" src="{{ $model->manga->photo }}" class="rounded" alt="...">
                <span class="ml-1">{{ $model->manga->name }}!</span> 

                @php
                    $label = "Chapter $model->chapter is out $model->release."
                @endphp

                {!! anchorNewTab($model->url, $label) !!}
                
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

        @elseif($type == 'newUser')

            <div 
                class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark" 
                role="alert" 
                data-id="{{ $notification->id }}"
            >

                {{ __( $model->email .'['.$model->name.']' ) }}
                <span class="text-info"> join the party</span>
                {!! $model->joined !!}.
                
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
        @else

            <div 
                class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark font-weight-bold" 
                role="alert" 
                data-id="{{ $notification->id }}"
            >
                {{-- welcome msg --}}
                @if ($notification->type == 'App\Notifications\WelcomeMessageNotification')
                    <span class="text-success">{{ __('Hello') }}</span>
                    <span class="text-info">{{ auth()->user()->email }}</span>
                    <span class="text-danger">!!!!!</span>
                    <br>
                    
                    {!! trans('lang.welcome_message') !!} 

                    <img class="mt-n2" style="height: 30px; width:30px;" src="{{ asset('images/heart_emoji.jpg') }}" class="rounded" alt="...">    
                @else

                    {!! trans('lang.'.$notification->data) !!}

                @endif

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>

            </div>

        @endif
        
        
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