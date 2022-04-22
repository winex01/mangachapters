<p class="mt-2 text-muted">
    Hi, I’m Winnie the creator of {{ config('app.name') }}. 
    The main purpose of this website is to help people like me
    who read Manga/Manhwa/Manhua across multiple different website to
    manage all in one place. By bookmarking the Manga/Manhwa/Manhua you will
    get notified once a new chapter is release. 
    Disclaimer, this is not a website where you can read Manga/Manhwa/Manhua it only redirects 
    you to the source of the new release or updates. Soo it's more of like a blog/news that notifies you
    everytime a new chapter is realease. You can register 
    <a href="{{ url('/register') }}">here</a>
    . You can contact me also at the 

    @if (request()->is('auth/*'))
        <a href="{{ url('auth/contact') }}">contact</a>
    @else
        <a href="{{ url('/contact') }}">contact</a>
    @endif
    page menu.
</p>

<p class="text-muted">
    If you see your website links here and you want it to be remove, then please send me a message.
</p>