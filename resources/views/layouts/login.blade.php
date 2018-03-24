@if (Route::has('login'))
    <div class="top-right links">
        @auth
            <a href="{{ url('/home') }}">Home</a>
        @else
          <a href="{{ url('/login/google') }}">
            <span class="glyphicon glyphicon-log-in"></span>Login (Google)
          </a>
        @endauth
    </div>
@endif
