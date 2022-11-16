@extends('layout')

@section('content')
<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Success</div>
                    <div class="card-body">
                        <p class="alert alert-info">Success, Please click link below.</p>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <a class="" href="{{$link}}">Change Password</a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection