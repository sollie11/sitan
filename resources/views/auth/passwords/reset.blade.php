@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Reset Password') }}</div>
				@include('alerts')
			</div>
			
			
			
			
			
			
			
			
			
			
			
			
			
			
						
			
			
			<div class="card-body">
			
			
			
			
				<form method="POST" action="{{ route('password.update') }}">
					@csrf

<input type="hidden" name="token" value="{{ $token }}">

<div class="form-group">
	<label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
	<div class="form-input">
		<input id="email" type="email" class="form-inputinput @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
	</div>
</div>

<div class="form-group">
	<label for="password" class="form-label">{{ __('Password') }}</label>
	<div class="form-input">
		<input id="password" type="password" class="form-inputinput @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
	</div>
</div>

<div class="form-group">
	<label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
	<div class="form-input">
		<input id="password-confirm" type="password" class="form-inputinput" name="password_confirmation" required autocomplete="new-password">
	</div>
</div>

<div class="form-group">
	<div class="form-input">
		<button type="submit">{{ __('Reset Password') }}</button>
	</div>
</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
