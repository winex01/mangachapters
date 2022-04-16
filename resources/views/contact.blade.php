@extends('layouts.guest_blank')

@section('guest_blank_content')

    <h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Contact Us') }}</h6>

    
    <div class="container mt-3 col-md-6 col-md-offset-3">
        <form>
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
              <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            
            <div class="form-group">
              <label for="name">Name</label>
              <input type="name" class="form-control" id="name" placeholder="Name">
            </div>

            <div class="form-group">
                <label for="message">Message</label>
                <textarea class="form-control" name="message" id="message" rows="5"></textarea>
            </div>
            
            <button type="submit" class="btn btn-success">Send a message</button>
          </form>
    </div>
    

@endsection
