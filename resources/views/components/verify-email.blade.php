<div class="text-center mb-4">
    <a href="/logout" class="btn btn-outline-info">
        <i class="las la-arrow-left"></i>
        {{ __('Back to login') }}
    </a>
</div>

<div class="card">
    <div class="card-header">{{ __('Verify Your Email Address') }}
        <span class="text-success font-weight-bold">
            {{ auth()->user()->email }}
        </span>
    </div>
    
    <div class="card-body">
        @if (session('message'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
        @endif
        
        {{ __('Before proceeding, please check your email inbox or spam folder for a verification link.') }}
        {{ __('If you did not receive the email') }},
        <form class="d-inline" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
        </form>
    </div>
</div>