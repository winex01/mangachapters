@extends(backpack_view('layouts.plain'))
        
@section('content')
    <div class="justify-content-center" style="margin-top: -300px;">

        <div class="text-center mb-4">
            <a href="/logout" class="btn btn-outline-info">
                <i class="las la-arrow-left"></i>
                {{ __('Back to login') }}
            </a>
        </div>
        
        <x-verify-email></x-verify-email>
            
    </div>
@endsection




