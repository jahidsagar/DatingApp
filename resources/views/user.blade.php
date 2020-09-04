<!DOCTYPE html>
<html lang="en">

<head>
    @include('static.head')
    <style>
        .success{
            color: red;
        }
    </style>
    <title>Welcome to Dating Site</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            @include('static.navbar')
        </div>
    </div>

    <div class="container">
        <div class="row">
            @if(Session::has('image'))
                <h4 class="text-center text-success">{{ Session::get('image') }}</h4>
            @endif
            @error('userImage')
                <h4 class="text-center text-success">{{ error('userImage') }}</h4>
            @enderror
            <div class="col-md-4">
                <?php
                $link = auth::user()->id.".jpeg";
                ?>
                <img src="storage\{{$link}}" class="img-thumbnail" width="200" height="200"
                    alt="please upload image">
                <p>&nbsp;</p>
                <div class="card" style="width: 18rem;">
                    <form action="{{ URL::to('/image') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="userImage">Upload image</label>
                            <input type="file" class="form-control-file" id="userImage" name="userImage" accept="image/jpeg" required>
                            <button type="submit" class="btn btn-info navbar-btn">Submit</button>
                        </div>
                    </form>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><b>Name: </b>{{ Auth::user()->name }}</li>
                        <li class="list-group-item"><b>Gender: </b>{{ Auth::user()->gender }}</li>
                        <li class="list-group-item"><b>Birth date: </b>{{ Auth::user()->dob }}</li>
                        @php
                        $dob = Auth::user()->dob;
                        $dob = explode("-", $dob);
                        $age = (date("md", date("U", mktime(0, 0, 0, $dob[1], $dob[2], $dob[0]))) >
                        date("md")
                        ? ((date("Y") - $dob[0]) - 1)
                        : (date("Y") - $dob[0]));
                        @endphp
                        <li class="list-group-item"><b>Age: </b>{{ $age }}</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <table class="table table-striped" id="usersTable">
                    <thead>
                        <tr>
                            {{-- <th scope="col">#</th> --}}
                            <th scope="col">Name</th>
                            <th scope="col">Gender</th>
                            <th scope="col">Age</th>
                            <th scope="col">Distance (Km)</th>
                            <th scope="col">Image</th>
                            <th scope="col">Liked</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Otto</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                            <td>@mdo</td>
                        </tr> --}}

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
@include('static.footer')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script>
    // getting all users
    $users = <?php echo $users; ?> ;
    // logged in user latitude and longitude
    $userLatitude = {{ Auth::user()->latitude }};
    $userLongitude = {{ Auth::user()->longitude }};
    $userId = {{ Auth::user()->id }};
    //function for calculating Date
    function age(date) {
        var dob = date;
        var year = Number(dob.substr(0, 4));
        var month = Number(dob.substr(4, 2)) - 1;
        var day = Number(dob.substr(6, 2));
        var today = new Date();
        var age = today.getFullYear() - year;
        if (today.getMonth() < month || (today.getMonth() == month && today.getDate() < day)) {
            age--;
        }
        return age;
    }
    
    // distance calculation function
    function getDistanceFromLatLonInKm(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2 - lat1); // deg2rad below
        var dLon = deg2rad(lon2 - lon1);
        var a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c; // Distance in km
        return d;
    }

    function deg2rad(deg) {
        return deg * (Math.PI / 180)
    }


    for ($i = 0; $i < $users.length; $i++) {
        $dob = $users[$i].dob.split('-').join('');
        $users[$i].dob = age($dob);
        $dis = getDistanceFromLatLonInKm($userLatitude,$userLongitude,$users[$i].latitude,$users[$i].longitude)
        $users[$i]['distance'] = $dis;
        if($users[$i]['distance'] <= 5 && $userId != $users[$i].id){
            $img = "storage\\"+$users[$i].id+".jpeg";
            $('#usersTable').append("<tr ><td >"+$users[$i].name+"</td><td>"+$users[$i].gender+"</td><td>"+$users[$i].dob+"</td><td>"+$users[$i].distance.toFixed(2)+"</td><td><img src='"+ $img+"' class=\"img-thumbnail\" /></td><td><span class=\"glyphicon glyphicon-heart\" aria-hidden=\"true\" id='"+$users[$i].id+"'></span></td></tr>");
        }
    }
    $( "span.glyphicon" ).click(function(element) {
        var class_name = this.className.split(' ').pop() ;
        if(class_name == 'success'){
            $url = "{{ URL::to('/dislike') }}";
            $_token = "<?php echo csrf_token() ?>";
            var id = this.id;
            $.ajax({
            type:'POST',
            url:$url,
            data:{
                _token: $_token ,
                user_id: $userId,
                liked_id: this.id,
                },
            success:function(res) {
                $('#'+id).removeClass('success');
                // sweet alart
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Disliked successfull!!',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Sorry! Try again later.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
        }else{
            $url = "{{ URL::to('/like') }}";
        $_token = "<?php echo csrf_token() ?>";
        var id = this.id;
        $.ajax({
            type:'POST',
            url:$url,
            data:{
                _token: $_token ,
                user_id: $userId,
                liked_id: this.id,
                },
            success:function(res) {
                $('#'+id).addClass('success');
                // sweet alart
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'Like successfully recorded',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: 'Sorry! Try again later.',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        });
        }
        
    });
    $( document ).ready(function() {
        $url = "{{ URL::to('/match') }}";
        
        $.ajax({
            type:'GET',
            url: $url,
            data:{},
            success:function(res) {

                res.forEach(element => {
                    $('#'+element.liked_id).addClass('success');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                
            }
        });

        // table search
        $("#search").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#usersTable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });

    // get like 
    function getLike(){
        $url = "{{ URL::to('/getmatch') }}";
        $_token = "<?php echo csrf_token() ?>";
        $.ajax({
            type:'POST',
            url:$url,
            data:{
                _token: $_token ,
                user_id: $userId,
                },
            success:function(res) {
                //on success make notification
                res.forEach(element => {
                    Swal.fire({
                        position: 'bottom-right',
                        title: element.name+' liked you.',
                        showConfirmButton: false,
                        imageUrl: 'storage/love.jpg',
                        imageWidth: 50,
                        imageHeight: 50,
                        imageAlt: 'Custom image',
                        timer: 1500
                    });
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                //no error will be shown
                //coz it repeatedly calls
            }
        });
    }

    var m = setInterval('getLike()', 5000);
</script>
</html>
