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