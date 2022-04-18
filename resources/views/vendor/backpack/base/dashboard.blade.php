@extends(backpack_view('blank'))

@section('content')

    @can('admin_widgets')
        @include(backpack_view('inc.custom_my_widgets'))

        <div class="mb-2">
            <a class="text-muted text-decoration-none" href="javscript::void()" onclick="$('.my-widgets').toggle()">{{ __('Toggle widgets') }}</a>
        </div>
        
    @endcan

    @unless (auth()->user()->hasVerifiedEmail())
        <x-verify-email></x-verify-email>
    @endunless
        
    @php
        // all notification except for chapters
        $notifications = auth()->user()
                    ->unreadNotifications()
                    ->where('type', '!=', 'App\Notifications\NewChapterNotification')
                    ->simplePaginate(config('appsettings.home_chapters_entries'));
    @endphp

    @foreach ($notifications as $notification)
        <div 
            class="alert alert-secondary alert-dismissible fade show text-dark font-weight-bold other-notification" 
        role="alert" 
            data-id="{{ $notification->id }}"
        >
            @if ($notification->type == 'App\Notifications\WelcomeMessageNotification')

                <span class="text-success">{{ __('Hello') }}</span>
                <span class="text-info">{{ auth()->user()->email }}</span>
                <span class="text-danger">!!!!!</span>
                <br>
                
                {!! trans('lang.welcome_message') !!} 

                <img class="mt-n2" style="height: 30px; width:30px;" src="{{ asset('images/heart_emoji.png') }}" class="rounded" alt="...">    
            
            @elseif ($notification->type == 'App\Notifications\NewUserNotification')

                @php
                    $user = modelInstance($notification->data['model'])->find($notification->data['id']);
                @endphp
                
                @if ($user)
                    {{ __( $user->email .'['.$user->name.']' ) }}
                    <span class="text-info"> join the party</span>
                    {!! $user->joined !!}.
                @else
                    <p class="text-muted">
                        {{ __('User was deleted.') }}                    
                    </p>    
                @endif

            @elseif ($notification->type = 'App\Notifications\ContactUsNotification')
                @dump($notification->data)
            @else
                {!! trans('lang.'.$notification->data) !!}
            @endif

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span  class="" aria-hidden="true">&times;</span>
            </button>

        </div>

    @endforeach
    


    {{-- Chapter Notification --}}
    @php
        $notifications = auth()->user()
                    ->unreadNotifications()
                    ->where('type', 'App\Notifications\NewChapterNotification')
                    ->simplePaginate(config('appsettings.home_chapters_entries'));
    @endphp

    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">
            {{ trans('lang.notifications') }}
            
            @if (count($notifications))
                <a class="text-muted" href="javascript:void(0)" id="mark-all-as-read">{{ trans('lang.chapter_mark_all_as_read') }}</a>
            @endif

        </h6>

        @foreach ($notifications->chunk(3) as $notification)

        <div class="row">

            @foreach ($notification as $noty)
                @php
                    $chapter = modelInstance('Chapter')
                                ->with('manga')
                                ->notInvalidLink()
                                ->find($noty->data['id']);
                    
                    //* if item not exist, maybe deleted, then mark it as read.
                    if (!$chapter) {
                        $notification->markAsRead();
                        continue;
                    }
                @endphp
                
                <x-chapter-card :chapter="$chapter" :notification="$noty"></x-chapter>

            @endforeach
            
        </div>

        @endforeach

        <small class="d-block text-right mt-3">
            @if ($notifications)
                {{ $notifications->links() }}
            @endif
        </small>

    </div>
    {{-- End Chapter Notification --}}

@endsection

@push('after_scripts')
<script>
    $('.mark-as-read').on('click', function () {
        markAdRead($(this).attr('data-id'));
    });
    
    $('.other-notification').on('close.bs.alert', function () {
        markAdRead($(this).attr('data-id'));
    });

    $('#mark-all-as-read').click(function (e) { 
        e.preventDefault();
        
        const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success ml-1',
		    cancelButton: 'btn btn-secondary'
		  },
		  buttonsStyling: false
		});


      	// show confirm message
		swalWithBootstrapButtons.fire({
		  text: "{{ trans('lang.chapter_are_you_sure') }}",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonText: "{{ trans('lang.chapter_confirm') }}",
		  cancelButtonText: "{{ trans('lang.chapter_cancel') }}",
		  reverseButtons: true,
		}).then((result) => {
		    if (result.isConfirmed) {

                var dataArray = $('.mark-as-read').map(function(){
                    return $(this).data('id');
                }).get();

                $.ajax({
                    type: "post",
                    url: "{{ route('dashboard.markAllAsReadChapterNotification') }}",
                    data: {
                        ids : dataArray
                    },
                    success: function (response) {
                        console.log(response);
                        $('.chapter-card').hide();
                        
                        // Show a success notification bubble
                        new Noty({
                            type: "success",
                            text: "{!! '<strong>'.trans('backpack::crud.delete_confirmation_title').'</strong><br>'.trans('backpack::crud.delete_confirmation_message') !!}"
                        }).show();
                    }
                });
            }
		});//end swal       
    });

    function markAdRead(attrId) {
        $.ajax({
            type: "post",
            url: "{{ route('dashboard.markAsReadNotification') }}",
            data: {
                // ids : $(this).attr('data-id')
                id : attrId
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
    }
</script>
@endpush

@push('after_styles')
    <style>
        .my-widgets{
            display:none;
        }
    </style>
@endpush