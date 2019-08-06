@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Register') }}</div>
				@include('alerts')
			</div>




			
				
				
				
				
				
				
				
				
				
				
				
				
				
				
			<div class="card-body">
			
			
			
			
				<form method="POST" action="{{ route('register') }}">
					@csrf



<div class="form-group">
	<label for="name" class="form-label">{{ __('Name') }}</label>
	<div class="form-input">
		<input id="name" type="text" class="form-inputinput @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
	</div>
</div>

<div class="form-group">
	<label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
	<div class="form-input">
		<input id="email" type="email" class="form-inputinput @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
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
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit">{{ __('Register') }}</button>
	</div>
</div>

				</form>	
			</div>
		</div>
	</div>
</div>
@endsection