<!DOCTYPE html>
<html lang="en">

<head>
    @include('static.head')
    <title>Welcome to Dating Site</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <a class="navbar-brand" href="#">Dating Site</a>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        @if (request()->is('/'))
                            <li><a href="signin">Sign in</a></li>
                        @endif
                        @if (request()->is('signin'))
                            <li><a href="/">Register</a></li>
                        @endif
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-2"></div>
            <form action="{{ URL::to('/login') }}" name="signin" class="col-md-4 col-sm-8" method="POST">
                @csrf
                
                @if(Session::has('registration'))
                <h4 class="text-center text-success">{{ Session::get('registration') }}</h4>
                @endif
                @if(Session::has('signin_error'))
                <h4 class="text-center text-danger">{{ Session::get('signin_error') }}</h4>
                @endif
                <h3 class="text-center">Please sign in</h3>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                        required>
                    <p class="text-danger" id="error_name"></p>
                </div>
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter your password" required>
                    <p class="text-danger" id="error_password"></p>
                </div>
                {{-- <div class="checkbox">
                    <label><input type="checkbox" name="remember"> Remember me</label>
                </div> --}}
                <button type="submit" class="btn btn-default">Submit</button>
            </form>
        </div>
    </div>
</body>
@include('static.footer')
<script>

</script>

</html>
