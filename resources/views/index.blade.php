@extends('layout')

@section('content')
    <main class="login-form">
        <div class="container">

            <div class="row justify-content-center">
                <h1>emohr Authentication and Registration</h1>
            </div>
            
            <br />
            @if(Session::has('success'))
                <div class="alert alert-danger">{{ Session::get('success') }}</div>
            @endif

            <br />

            <div class="row justify-content-center">
                      
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Login</div>
                        <div class="card-body">

                            <form action="{{ route('login') }}" method="POST" id="handleAjaxLogin">
                                @csrf

                                <div id="errors-list-1"></div>

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">E-Mail
                                        Address</label>
                                    <div class="col-md-6">
                                        <input type="email" id="email" class="form-control" name="email"
                                            required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button id="login" class="btn btn-primary">
                                        Login
                                    </button>
                                </div>
                            </form>

                            <br />

                            <div id="qrcode">
                            </div>  

                        </div>
                    </div>
                    
                    
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">Register</div>
                        <div class="card-body">

                            <form id="handleAjaxRegistration">

                                @csrf

                                <div id="errors-list-2"></div>

                                <div class="form-group row">
                                    <label for="firstname" class="col-md-4 col-form-label text-md-right">First Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="firstname" class="form-control" name="firstname" required
                                            autofocus>
                                        @if ($errors->has('firstname'))
                                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="lastname" class="col-md-4 col-form-label text-md-right">Last Name</label>
                                    <div class="col-md-6">
                                        <input type="text" id="lastname" class="form-control" name="lastname" required
                                            autofocus>
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
                                            required autofocus>
                                        @if ($errors->has('email'))
                                            <span class="text-danger">{{ $errors->first('email') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Register
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <script src="{{ asset('js/qrcode.js') }}"></script>

    <!-- Registration JS -->
    <script type="text/javascript">
            /*------------------------------------------
            --------------------------------------------
            Submit Event
            --------------------------------------------
            --------------------------------------------*/
            $(document).ready(function() {

            $('#handleAjaxRegistration').on('submit', function(e) {
                               
                e.preventDefault();

                $.ajax({
                    url: "{{ route('register') }}",
                    data: $('#handleAjaxRegistration').serialize(),
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        if (data.status) {
                            $(".alert").remove();
                            $('#handleAjaxRegistration')[0].reset();
                            $("#errors-list-2").append(
                                    "<div class='alert alert-success'>" + data.message +
                                    "</div>");
                        } else {

                            $(".alert").remove();
                            $.each(data.errors, function(key, val) {
                                $("#errors-list-2").append(
                                    "<div class='alert alert-danger'>" + val +
                                    "</div>");
                            });
                        }

                    }
                });

                return false;
            });

 
 
            // Login

            /*------------------------------------------
            --------------------------------------------
            Submit Event
            --------------------------------------------
            --------------------------------------------*/
            $('#handleAjaxLogin').on("submit", function(e) {
                
                e.preventDefault();

                 $.ajax({
                    url: "{{ route('login') }}",
                    data: $(this).serialize(),
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {

                        $(e).find("[type='submit']").html("Login");

                        if (data.status) {
                            $('#handleAjaxLogin')[0].reset();
                            var qrcode = new QRCode("qrcode");
                            qrcode.clear();
                            qrcode.makeCode(data.redirect);
                            $('#qrcode').show();
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
    </script>
@endsection
