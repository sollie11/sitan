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



				
				<form method="POST" action="{{ route('password.email') }}">
					@csrf



<div class="form-group">
	<label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
	<div class="form-input">
		<input id="email" type="email" class="form-inputinput @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
	</div>
</div>

<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit">{{ __('Send reset link') }}</button>
	</div>
</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection
























