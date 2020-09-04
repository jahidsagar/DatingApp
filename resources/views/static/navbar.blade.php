<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Dating Site</a>
        </div>
        <ul class="nav navbar-nav navbar-right">
            @if (request()->is('/'))
                <li><a href="signin">Sign in</a></li>
            @endif
            @if (request()->is('/signin'))
                <li><a href="/">Register</a></li>
            @endif
            @auth
                <li class="form-group">
                    <input type="text" class="form-control navbar-btn" placeholder="Search..." id="search">
                </li>
                <li>&nbsp;</li>
                <li>
                <form action="{{ URL::to('/logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger navbar-btn">Log out</button>
                </form>
            </li>
            @endauth
        </ul>
        
    </div>
</nav>