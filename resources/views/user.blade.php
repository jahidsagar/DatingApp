<!DOCTYPE html>
<html lang="en">

<head>
    @include('static.head')
    <style>
        .success{
            color: red;
        }
        @media screen and (max-width: 995px) {
            .right {
                float: right;
            }
            .display{
                display: none;
            }
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
                <h4 class="text-center text-danger">Image must be JPEG & max size 1024Kb.</h4>
            @enderror
            <div class="col-md-4">
                <?php
                $link = auth::user()->id.".jpeg";
                ?>
                <img src="{{ asset('image') }}/{{$link}}?<?php echo time()?>" class="img-thumbnail" width="200" height="200"
                    alt="please upload image">
                {{-- <p>&nbsp;</p> --}}
                <div class="card right" style="width: 18rem;">
                    <form action="{{ URL::to('/imageupload') }}" class="display" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="userImage">Upload image</label>
                            <input type="file" class="form-control-file" id="userImage" name="userImage" accept="image/jpeg" required>
                            <button type="submit" class="btn btn-info navbar-btn">Submit</button>
                        </div>
                    </form>
                    <ul class="list-group list-group-flush ">
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
@include('static.userjs')

</html>
