@extends('layout')

@section('content')
    <main class="login-form">
        <div class="container">

            <div class="row justify-content-center">
                <h1>emohr User Profile</h1>
            </div>
            
            <br />

            <div class="row justify-content-center">
                      



<div class="col-md-12">
    <div class="card">
        <div class="card-header">
            <span>User Profile</span>
            <span style="float:right"><a class="btn btn-success" href="{{ route('list') }}">Users List</a></span>            
        </div>
        <div class="card-body">

            <form id="handleAjaxProfile" enctype="multipart/form-data">

                @csrf

                <div id="errors-list-1"></div>

                <div class="row">
                <div class="col-9">

                <div class="form-group row">
                    <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>
                    <div class="col-md-6">
                        <input type="text" id="firstname" class="form-control" name="firstname" required
                            autofocus value="{{ $user->firstname }}">
                        @if ($errors->has('firstname'))
                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>
                    <div class="col-md-6">
                        <input type="text" id="lastname" class="form-control" name="lastname" required
                            autofocus value="{{ $user->lastname }}">
                        @if ($errors->has('lastname'))
                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail
                        Address</label>
                    <div class="col-md-6">
                        <input type="email" id="email" class="form-control" name="email"
                            disabled value="{{ $user->email }}">
                        @if ($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                </div>

            </div>

            <div class="col-3">
                <div class="form-group row">
                    <label for="avatar" class="col-md-4 col-form-label text-md-right">Avatar</label>
                    <br />
                    <img id="newavatar" style="width:150px; height:150px" class="img-thumbnail" src="{{ asset('images') . '/' . ($user->avatar ?? 'default.png') }}" />
                    <br />
                    <input type="file" id="avatar" class="form-control" name="avatar" onchange="mainThumbnailUrl(this)">
                </div>
            </div>

        </div>
        <div class="row">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                    &nbsp;
                    <a  class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
                </div>
            </form>
        </div>
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