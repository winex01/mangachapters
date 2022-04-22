@extends('layouts.guest_blank')

@section('guest_blank_content')

    <h6 class="border-bottom border-gray pb-2 mb-0">
        <a href="/">
            <i class="las la-arrow-left"></i>
            {{ __('Back to home') }}
        </a>
    </h6>

    @foreach ($chapters->chunk(3) as $chunks)

        <div class="row">

        @foreach ($chunks as $chapter)

            <x-chapter-card :chapter="$chapter"></x-chapter>
            
        @endforeach
        
        </div>
    @endforeach

    <small class="d-block text-right mt-3">
        {{ $chapters->links() }}
    </small>

@endsection
