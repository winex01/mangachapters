<div class="mt-3 col-md-6 offset-md-3">

    @if (session('message'))
      <div class="alert alert-success" role="alert">
          {{ __('Thanks for contacting us!') }}
          {{ __('We will get back to you soon!') }}
      </div>
    @endif

    <form method="POST" action="{{ route('contact.send') }}">
        @csrf

        @php
            $oldEmail = old('email');
            $oldName = old('name');

            $focusMsg = false;

            if (auth()->check()) {
                $oldEmail = auth()->user()->email;
                $oldName = auth()->user()->name;
                $focusMsg = true;
            }
        @endphp

        <div class="form-group">
          <label for="email">Email address</label>
          <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" aria-describedby="email" placeholder="Enter email" value="{{ $oldEmail }}">
          <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        
          @if ($errors->has('email'))
              <span class="invalid-feedback">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
        </div>
        
        <div class="form-group">
          <label for="name">Name</label>
          <input type="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" placeholder="Enter Name" value="{{ $oldName }}">
          
          @if ($errors->has('name'))
              <span class="invalid-feedback">
                  <strong>{{ $errors->first('name') }}</strong>
              </span>
          @endif

        </div>

        <div class="form-group">
            <label for="message">Message</label>
            <textarea @if($focusMsg) autofocus @endif  name="message" class="form-control{{ $errors->has('message') ? ' is-invalid' : '' }}" id="message" rows="5" placeholder="Please enter your message here...">{{ old('message') }}</textarea>
        
            @if ($errors->has('message'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('message') }}</strong>
                </span>
            @endif
        </div>
        
        <button type="submit" class="btn btn-info">Send a message</button>
      </form>
</div>