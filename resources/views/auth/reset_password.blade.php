@extends('layout')

@section('content')
<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Change Password</div>
                    <div class="card-body">
                        @if(Session::has('success'))
                        <p class="alert alert-info">{{ Session::get('success') }}</p>
                        @endif
                        @if(Session::has('error'))
                        <p class="alert alert-warning">{{ Session::get('error') }}</p>
                        @endif
                        <form action="{{ route('reset.password') }}" method="POST">
                            <input type="hidden" value="{{$token}}" name="access_token">
                            @csrf
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="password" class="form-control" name="password" required>
                                    @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Confirm Password</label>
                                <div class="col-md-6">
                                    <input type="password" id="confirm_password" class="form-control" name="confirm_password" required>
                                    @if ($errors->has('confirm_password'))
                                    <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection