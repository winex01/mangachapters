@extends('layouts.guest_blank')

@section('guest_blank_content')

    <h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Contact Us') }}</h6>

    <div class="mt-3 col-md-6 offset-md-3">
        <form method="POST" action="{{ route('contact.send') }}">
            @csrf

            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" aria-describedby="email" placeholder="Enter email" value="{{ old('email') }}">
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            
              @if ($errors->has('email'))
                  <span class="invalid-feedback">
                      <strong>{{ $errors->first('email') }}</strong>
                  </span>
              @endif
            </div>
            
            <div class="form-group">
              <label for="name">Name</label>
              <input type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Enter Name" value="{{ old('name') }}">
              
              @if ($errors->has('name'))
                  <span class="invalid-feedback">
                      <strong>{{ $errors->first('name') }}</strong>
                  </span>
              @endif

            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea name="message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" id="message" rows="5" placeholder="Please enter your message here...">{{ old('message') }}</textarea>
            
                @if ($errors->has('message'))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first('message') }}</strong>
                    </span>
                @endif
            </div>
            
            <button type="submit" class="btn btn-success">Send a message</button>
          </form>
    </div>
    

@endsection
