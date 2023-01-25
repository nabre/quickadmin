@extends('nabre-quickadmin::quick.auth')
@section('CONTENT')

<div class="mb-4 text-sm text-gray-600">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
</div>

<form method="POST" action="{{ route('password.email') }}" >
    @csrf

    <!-- Email Address -->
    <div class="row mb-3">
        <label for="email" class="col-sm-2 col-form-label">{{ __('Email') }}</label>
        <div class="col-sm-10">
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required
                autofocus>
        </div>

    <div class="flex items-center justify-end mt-4">
        <button class="btn btn-primary">{{ __('Email Password Reset Link') }}</button>
    </div>
</form>

@endsection
