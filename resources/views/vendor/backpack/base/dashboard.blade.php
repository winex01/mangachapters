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

    {{-- @php
        $firstLoop = true;
        $tempValue = null;
    @endphp --}}
    
    {{-- @forelse (auth()->user()->unreadNotifications  as $notification) --}}
        
        {{-- @if ($firstLoop)
            <a class="btn btn-danger btn-sm mb-3" href="javascript:void(0)" id="clear-all-notifications">Clear all notification(s).</a>
        @endif --}}

        @php
            // $firstLoop = false;
            // $data = $notification->data;
            // $model = null;
            // $type = null;

            // if ($notification->type == 'App\Notifications\NewChapterNotification') {
            //     $model = modelInstance($data['model'])->with('manga')->find($data['id']);
            //     $type = 'newChapter';
                
            // }elseif ($notification->type == 'App\Notifications\NewUserNotification') {
            //     $model = modelInstance($data['model'])->find($data['id']);
            //     $type = 'newUser';
                
            // }else {
            //     $type = 'generalNotification';
            //     $model = true; // assign model to true so it wont markAsRead at the bottom
            // }

            // // if no data is find then perhaps i deleted the notification in database, so escape this loop
            // if (!$model) {
            //     $notification->markAsRead();
            //     continue;
            // }
        @endphp

        {{-- @if ($notification->type == 'App\Notifications\NewChapterNotification') --}}

           {{--  @php
                $chapter = modelInstance($data['model'])->with('manga')->find($data['id']);
            @endphp


            {!! trans('lang.chapter_notification_card', [
                'image' => $chapter->manga->photo,
                'title' => $chapter->manga->title,
                'link' => anchorNewTab($chapter->url, trans('lang.chapter_notification_description', [
                            'chapter' => $chapter->chapter, 
                            'release' => $chapter->release, 
                        ]) ) 
            ]) !!} --}}

        {{-- @elseif($type == 'newUser') --}}

            {{-- <div 
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
            </div> --}}
            
        {{-- @else --}}

            {{-- <div 
                class="chapter-alert alert alert-secondary alert-dismissible fade show text-dark font-weight-bold" 
                role="alert" 
                data-id="{{ $notification->id }}"
            >
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

            </div> --}}

        {{-- @endif --}}
        
        
    {{-- @empty
        @php
            $tempValue = 'No notification(s).';
        @endphp
    @endforelse

    <p id="temp">{{ $tempValue }}</p> --}}
    


    {{-- Recent Chapters --}}
    @php
        $chapters = auth()->user()
                    ->unreadNotifications()
                    ->where('type', 'App\Notifications\NewChapterNotification')
                    ->simplePaginate(config('appsettings.home_chapters_entries'));
    @endphp

    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('lang.chapter_your_bookmark') }}</h6>

        @foreach ($chapters->chunk(3) as $chunks)

        <div class="row">

            @foreach ($chunks as $chapter)

                @php
                    $chapter = modelInstance($chapter->data['model'])->with('manga')->find($chapter->data['id']);
                @endphp

                <x-chapter :chapter="$chapter"></x-chapter>

            @endforeach
            
        </div>

        @endforeach

        <small class="d-block text-right mt-3">
            @if ($chapters)
                {{ $chapters->links() }}
            @endif
        </small>

    </div>
    {{-- End Recent Chapters --}}

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
{{-- TODO:: add total number of users registered --}}
{{-- TODO:: add total number of bookmarks --}}
{{-- TODO:: add total number of chapters scan --}}
{{-- TODO:: add total number of mangas --}}