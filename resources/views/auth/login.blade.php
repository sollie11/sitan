@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Login') }}</div>
				@include('alerts')
			</div>



















			<div class="card-body">
			
			
			
				<form method="POST" action="{{ route('login') }}">
					@csrf


					
<div class="form-group">
	<label for="email" class="form-label">{{ __('Email Address') }}</label>
	<div class="form-input">
		<input id="email" type="email" class="form-inputinput @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
	</div>
</div>

<div class="form-group">
	<label for="password" class="form-label">{{ __('Password') }}</label>
	<div class="form-input">
		<input id="password" type="password" class="form-inputinput @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
	</div>
</div>

<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<div class="form-check">
			<input class="form-inputcheck" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
			<label class="form-label" for="remember">{{ __('Remember Me') }}</label>
		</div>
	</div>
</div>

<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit">{{ __('Login') }}</button>&nbsp;&nbsp;&nbsp;
		@if (Route::has('password.request'))
		<div style="float: right;"><a class="colornodecor" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a></div>
		@endif
	</div>
</div>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection
