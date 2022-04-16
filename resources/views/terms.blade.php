@extends('layouts.guest_blank')

@section('guest_blank_content')

  <h6 class="border-bottom border-gray pb-2 mb-0">{{ __('Terms & Conditions') }}</h6>

    {{-- TODO:: --}}
    <p class="mt-2 text-muted">
    
        Welcome aboard <img class="mt-n2" style="height: 18px; width:18px;" src="{{ asset('images/heart_emoji.png') }}" class="rounded" alt="..."> . 
        
        Welcome to {{ config('app.name') }}. If you continue to browse and use this website you are agreeing to comply with and be bound 
        by the following terms and conditions of use, 
        The use of this website is subject to the following terms of use: 
    </p>

    <ul class="text-muted" style="list-style-type:square;">
      <li>
        The content of the pages of this website is for your general information and use only. It is subject to change without notice.
      </li>

      <br>

      <li>
        Neither we nor any third parties(Links) provide any warranty or guarantee as to the accuracy, timeliness, performance, 
        completeness or suitability of the information and materials found or offered on this website for any particular purpose. 
        You acknowledge that such information and materials may contain inaccuracies or errors and we expressly exclude liability 
        for any such inaccuracies or errors to the fullest extent permitted by law. 
      </li>

      <br>

      <li>
        Your use of any information or links on this website is entirely at your own risk, 
        for which we shall not be liable. It shall be your own responsibility to ensure that any content, 
        or information available through this website meet your specific requirements. 
      </li>
      <br>

      <li>
          Discussions about sex, religions and politics are prohibited.(Forums/Comments)
      </li>

    </ul>

@endsection
