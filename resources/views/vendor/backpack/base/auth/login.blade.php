{{-- @extends(backpack_view('layouts.plain')) --}}
@extends('layouts.guest_blank')

@push('before_styles')
{!! ReCaptcha::htmlScriptTagJsApi() !!}
@endpush

@section('guest_blank_content')
{{-- @section('content') --}}

{{-- https://adsterra.com/ --}}
@include(backpack_view('inc.ads'))

<div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12 col-xs-12">
        <form class="" role="form" method="POST" action="{{ route('backpack.auth.login') }}">
            {!! csrf_field() !!}
        
            <div class="form-group">
                <label class="control-label" for="{{ $username }}">{{ config('backpack.base.authentication_column_name') }}</label>
        
                <div>
                    <input type="text" class="form-control{{ $errors->has($username) ? ' is-invalid' : '' }}" name="{{ $username }}" value="{{ old($username) }}" id="{{ $username }}">
        
                    @if ($errors->has($username))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first($username) }}</strong>
                        </span>
                    @endif
                </div>
            </div>
        
            <div class="form-group">
                <label class="control-label" for="password">{{ trans('backpack::base.password') }}</label>
        
                <div>
                    <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password">
        
                    @if ($errors->has('password'))
                        <span class="invalid-feedback">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>
            
            @if (config('appsettings.recaptcha'))
                {{-- recaptcha --}}
                <div class="form-group ">
                    @if($errors->has('g-recaptcha-response'))
                    <div>
                        <small class="text-danger">{{ $errors->first('g-recaptcha-response') }}</small>
                    </div>
                    @endif
                    {!! htmlFormSnippet() !!} 
                </div>
            @endif
        
            <div class="form-group">
                <div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="remember"> {{ trans('backpack::base.remember_me') }}
                        </label>
                    </div>
                </div>
            </div>
        
            <div class="form-group">
                <div>
                    <button type="submit" class="btn btn-block btn-primary">
                        {{ trans('backpack::base.login') }}
                    </button>
                </div>
            </div>
        </form>

        @if (backpack_users_have_email() && config('backpack.base.setup_password_recovery_routes', true))
            <div class="text-center"><a href="{{ route('backpack.auth.password.reset') }}">{{ trans('backpack::base.forgot_your_password') }}</a></div>
        @endif
        @if (config('backpack.base.registration_open'))
            <div class="text-center"><a href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></div>
        @endif
    </div>
        
    <div class="mt-5 col-lg-6 col-md-8 col-sm-12 col-xs-12">
        <img src="images/anya-writing.png"
        class="img-fluid" alt="Anya">
    </div>
</div>



@endsection
