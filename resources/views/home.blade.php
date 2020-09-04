<!DOCTYPE html>
<html lang="en">

<head>
    @include('static.head')
    <title>Welcome to Dating Site</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            @include('static.navbar');
        </div>
    </div>
    <div class="container">
        <div class="row">

            @auth
                <p>something</p>
            @else
                <div class="col-md-4"></div>
                <form action="{{ URL::to('/registration') }}" name="registration" class="col-md-4" method="post">
                    @csrf
                    <p class="text-center"><b class="text-info">Please enter your credentials below</b></p>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name"
                            minlength="5" required>
                        <p class="text-danger" id="error_name"></p>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address:</label>
                        <input type="email" class="form-control" id="email" name="email"
                            placeholder="Enter your email address" required>
                        <p class="text-danger"></p>
                    </div>
                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" min="8" required
                            placeholder="Enter password">
                        <p class="text-danger"></p>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation ">Confirm password:</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation"
                            minlength="8" required placeholder="Confirm password">
                        @error('password')
                        <p class="text-danger">Password didn't match.</p>
                        @enderror
                    </div>
                    <div class="from-group">
                        <label for="birthday">Birthday:</label>
                        <input type="date" id="birthday" name="birthday" class="form-control" max=
                        <?php
                            echo date('Y-m-d');
                        ?> required>
                    </div>
                    <div class="form-group" style="margin-top: 3%;">
                        <label for="gender">Select gender</label>
                        <select name="gender" id="gender" class="form-control" required>
                            <option selected disabled>Select one</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="others">Others</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom: 2%;">
                        <label for="pwd">We need your location to find nearest partner.</label><br>
                        <button type="button" onclick="getLocation()" class="btn btn-info" style="margin-bottom: 1%;">Get
                            Location</button>

                        <div class="form-group">
                            <input type="text" class="form-control col-md-6" id="latitude" name="latitude"
                                placeholder="Latitude" readonly required style="margin-bottom: 1%;">
                            <input type="text" class="form-control col-md-6" id="longitude" name="longitude"
                                placeholder="Longitude" readonly required style="margin-bottom: 1%;">
                        </div>
                        @error('latitude')
                        <p id="location_error_latitude" class="text-danger">Latitude and Longitude is required</p>
                        @enderror
                        @error('Longitude')
                        <p id="location_error_longtitude" class="text-danger">Latitude and Longitude is required</p>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-default" style="margin-top: 3%;">Submit</button>
                </form>

            @endauth
        </div>
    </div>
</body>
@include('static.footer')
<script>
    var x = document.getElementById("location_error");

    function getLocation(e) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
        var latitude = $("form[name='registration'] input[name='latitude']");
        latitude.val(position.coords.latitude);
        var longitude = $("form[name='registration'] input[name='longitude']");
        longitude.val(position.coords.longitude);
    }
    //validate form
    // function validateForm(){

    //     return false;
    // }

</script>

</html>
