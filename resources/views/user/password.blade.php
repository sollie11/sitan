@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Change Password') }}</div>
				@include('alerts')
			</div>













			<div class="card-body">
				<form method="POST" action="{{ route('user-password-save') }}">
					@csrf


<div class="form-group">
	<div style="float: left">{{ __('Use this form to change your password.') }}</div>
	<div class="newline"></div>
</div>
					
<div class="form-group">
	<label for="oldpassword" class="form-label">{{ __('Old Password') }}</label>
	<div class="form-input">
		<input id="oldpassword" type="password" class="form-inputinput" name="oldpassword" required autofocus>
	</div>
</div>

<div class="form-group">
	<label for="password" class="form-label">{{ __('Password') }}</label>
	<div class="form-input">
		<input id="password" type="password" class="form-inputinput" name="password" required>
	</div>
</div>

<div class="form-group">
	<label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
	<div class="form-input">
		<input id="password_confirmation" type="password" class="form-inputinput" name="password_confirmation" required>
	</div>
</div>

<div class="form-group">
	<div class="form-inputright">
		<button type="submit" class="buttonsubmit">{{ __('Change Password') }}</button>
	</div>
</div>

                </form>
        	</div>
        </div>
    </div>
</div>
@endsection

