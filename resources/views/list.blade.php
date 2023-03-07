@extends('layout')

@section('content')
    <main class="login-form">
        <div class="container">

            <div class="row justify-content-center">
                <h1>emohr Users List</h1>
            </div>
            
            <br />

            <div class="row justify-content-center">
                      



<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <span>Users List</span>
            <span style="float:right">
                <a  class="btn btn-primary" href="{{ route('edit', [Auth::id(), Auth::user()->mobiletoken]) }}">User Profile</a>
                <a  class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
            </span>
        </div>
        <div class="card-body">
            
            <table id="userlist" class="table-bordered table table-striped">
                <thead>
                <th>#</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Avatar</th>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $user->firstname }}</td>
                    <td>{{ $user->lastname }}</td>
                    <td>{{ $user->email }}</td>
                    <td><img style="width:100px;height:100px" src="{{ asset('images/' . ($user->avatar ?? 'default.png')) }}" /></td>
                </tr>
                @endforeach
            </tbody>
            </table>

        </div>
    </div>
</div>

</div>
</div>
</main>

<script src="{{ asset('js/jquery.min.js') }}"></script>

<script type="text/javascript">
    /*------------------------------------------
    --------------------------------------------
    Submit Event
    --------------------------------------------
    --------------------------------------------*/
    $(document).ready(function() {

    $('#handleAjaxProfile').on('submit', function(e) {
                       
        e.preventDefault();

        let formData = new FormData(this);

        $.ajax({
            url: "{{ route('update', $user->id) }}",
            data: formData,
            contentType: false,
            processData: false,
            type: "POST",
            dataType: 'json',
            success: function(data) {

                if (data.status) {
                    $(".alert").remove();
                    $("#errors-list-1").append(
                            "<div class='alert alert-success'>" + data.message +
                            "</div>");
                } else {

                    $(".alert").remove();
                    $.each(data.errors, function(key, val) {
                        $("#errors-list-1").append(
                            "<div class='alert alert-danger'>" + val +
                            "</div>");
                    });
                }

            }
        });

        return false;
    });

});

function mainThumbnailUrl(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#newavatar').attr('src', e.target.result).attr('class', 'img-thumbnail').attr('style', 'width:150px;height:150px');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
</script>


@endsection