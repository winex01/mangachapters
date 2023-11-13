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

            @elseif ($notification->type == 'App\Notifications\AdminNewMangaNotification')
                @php
                    $user = modelInstance('User')->find($notification->data['by_user_id']);

                    $manga = modelInstance($notification->data['model'])->find($notification->data['id']);
                @endphp

                @if ($user && $manga)
                    {{ __( $user->email .'['.$user->name.']' ) }}
                    <span class="text-danger"> added manga </span>
                    <span class="text-info"> 
                        <a href="{{ linkToShow('manga', $manga->id) }}">{{ $manga->title }}</a>
                    </span>
                    {!! howLongAgo($manga->created_at) !!}.
                @else
                    <p class="text-muted">
                        {{ __('User/Manga was deleted.') }}                    
                    </p>    
                @endif
            
            @elseif ($notification->type == 'App\Notifications\AdminNewSourceNotification')
                @php
                    $user = modelInstance('User')->find($notification->data['by_user_id']);

                    $source = modelInstance($notification->data['model'])->find($notification->data['id']);
                @endphp

                @if ($user && $source)
                    {{ __( $user->email .'['.$user->name.']' ) }}
                    <span class="text-danger"> added source </span>
                    <span class="text-info"> 
                        <a href="{{ linkToShow('source', $source->id) }}">
                            {{ getDomainFromUrl($source->url) }}
                        </a> 
                    </span>
                    <span class="text-danger"> to </span>
                    <span class="text-info"> 
                        <a href="{{ linkToShow('manga', $source->manga->id) }}">{{ $source->manga->title }}</a>
                    </span>
                    {!! howLongAgo($notification->created_at) !!}.
                @else
                    <p class="text-muted">
                        {{ __('User/Source was deleted.') }}                    
                    </p>    
                @endif
                    
            @elseif ($notification->type == 'App\Notifications\ContactUsNotification')
                @php
                    $authUser = null;
                    $isAdmin = false;

                    if (isset($notification->data['auth_user'])) {
                        $authUser = modelInstance('User')
                            ->with('permissions')
                            ->with('roles')
                            ->find($notification->data['auth_user']);
                        
                        if ($authUser->hasPermissionTo('admin_reply_contact_us')) {
                            $isAdmin = true;
                        } 
                    }

                    // dump($isAdmin);

                @endphp

                <table class="table">
                    <tbody>
                        
                        @if (!$isAdmin)
                            <tr>
                                <th scope="row">Email:</th>
                                <td>{{ $notification->data['email'] }}</td>
                            </tr>
                        @endif
                        
                      <tr>
                        <th scope="row">Name:</th>
                        <td>
                            {{ $notification->data['name'] }}
                            @if ($isAdmin)
                                {{ __('(Administrator)') }}
                            @endif
                        </td>
                      </tr>
                      <tr>
                        <th scope="row">Message:</th>
                        <td>{{ $notification->data['message'] }}</td>
                      </tr>
                      <tr>
                        <th scope="row">Sent:</th>
                        <td>{!! howLongAgo($notification->created_at) !!}</td>
                      </tr>

                    {{-- if email provided exist in user then show row below, if not exist then the unauth contact us was used--}}
                    @can('admin_reply_contact_us')
                      @if ($authUser)
                        <tr>
                            <th scope="row">Action:</th>
                            <td>
                                <a 
                                    href="javascript:void(0)" 
                                    onclick="replyMessage(this)" 
                                    class="btn btn-info btn-sm"
                                    class-style="{{ $notification->data['auth_user'] }}" 
                                >
                                    <span class="ladda-label">
                                        <i class="las la-reply"></i>
                                        {{ __('Reply') }} 
                                    </span>
                                </a>
                            </td>
                        </tr>
                      @endif
                    @endcan

                    </tbody>
                  </table>
            @else
                @if (!is_array($notification->data))
                    {!! trans('lang.'.$notification->data) !!}
                @endif
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


<div class="alert alert-success"role=alert> 

    <p>
    Dear users,It is with a heavy heart that I must inform you of a difficult decision we have had to make. Due to financial constraints, we regret to announce that our server will no longer be able to sustain its operations beyond the end of this month.
            
            We understand the impact that this may have on our tight-knit community, and we want to make this transition as smooth as possible for all of you.
    </p>
    
    
    <p>
            You can export your Manga/Manhwa/Manhua bookmarks data to Excel, PDF or Etc.:
    </p>
    
    <ol>
            <li> Navigate to Manga's | Manhwa's or click <a href="http://mangachapters.test/manga?add_scope_show_only=whereBookmarkedBy&persistent-table=true">Click Here!</a> </li>
            <li>Select filter <b>Show Only</b> and select Bookmark at upper left in the table. (If you click the link above no need to do this)</li>
            <li>At the bottom right of the table there is an <b>Export</b> button. </li>
            <li>You can also select what column to be exported, just check column at the <b>Column Export</b> at the bottom right. </li>
    </ol>
    
            <p>
            <ul>
                    <li>
                            Deadline: The server will remain operational until the end of this month.
                    </li>
                    <li>
                            We recommend exploring <a href="https://tachiyomi.org/">Tachiyomi</a> as an alternative website for your needs.
                    </li>
                    <li>
                            If you have any question please <a href="http://mangachapters.test/auth/contact">contact us here</a> or email us at:manghwua@gmail.com.  Thank you for your understanding and God bless.
                    </li>
            </ul>
            </p>
    </div>


<div class="my-3 p-3 bg-white rounded shadow-sm">
    
    @if (config('appsettings.paypal'))
        <div class="row mb-n2" style="margin-top: -43px;">
            <x-donate-paypal></x-donate-paypal>
        </div>
    @endif
    
    @php
        $notice = config('settings.appsettings_dashboard_notice');
    @endphp

    @if ($notice != null)
        {!! $notice !!}
    @endif

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


    if (typeof replyMessage != 'function') {
        // Function to show the modal dialog
        function replyMessage(button) {
            const userId = $(button).attr('class-style');

            Swal.fire({
                input: 'textarea',
                inputLabel: 'Message',
                inputPlaceholder: 'Type your message here...',
                inputAttributes: {
                    'aria-label': 'Type your message here'
                },
                showCancelButton: true
            }).then((result) => {
                if (result.isConfirmed) {
                    const message = result.value;
                    // send message to server
                    sendMessage(userId, message);
                }
            });
        }
	}


    function sendMessage(userId, message) {
            
        $.ajax({
            type: "post",
            url: "{{ url('message/replyContactUs') }}",
            data: { 
                userId: userId,
                message: message
             },
            success: function (response) {
                console.log(response);
                if (response) {
                    if (response) {
                        swalSuccess();
                    }
                } else {
                    swalError();
                    new Noty({
                        type: "warning",
                        text: "<strong>{!! __('Scanned Error') !!}</strong><br>{!! __('Whoops something went wrong.') !!}"
                    }).show();			          	  
                }
            },
            error: function () {
                swalError();
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