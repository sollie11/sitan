@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">{{ __('Login') }}</div>
			<div class="card-body">
			
			
			
			
				<form method="POST" action="{{ route('login') }}">
					@csrf


					
<div class="form-group">
	<label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
	<div class="form-input">
		<input id="email" type="email" class="form-inputinput @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
		@error('email')
		<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
		@enderror
	</div>
</div>

<div class="form-group">
	<label for="password" class="form-label">{{ __('Password') }}</label>
	<div class="form-input">
		<input id="password" type="password" class="form-inputinput @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
		@error('password')
		<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
		@enderror
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
		<button type="submit" class="btn-primary">{{ __('Login') }}</button>
		@if (Route::has('password.request'))
		<a class="colornodecor" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
		@endif
	</div>
</div>

				</form>
			</div>
		</div>
	</div>
</div>
@endsection
