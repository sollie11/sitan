@extends('layouts.app')

@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">{{ __('Reset Password') }}</div>
			<div class="card-body">
			@if (session('status'))
				<div class="alert-success" role="alert">{{ session('status') }}</div>
			@endif
				
				<form method="POST" action="{{ route('password.email') }}">
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
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit" class="btn btn-primary">{{ __('Send Password Reset Link') }}</button>
	</div>
</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
