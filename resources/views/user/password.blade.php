@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row col50">
		<div class="card">
			<div class="card-header">{{ __('Change Password') }}</div>
			<div class="card-body">
<div class="form-group">
						
<div style="float: left">Use this form to change your password.</div>
<div class="newline"></div>
@if (session('status'))
<div style="float:right" role="alert">{{ session('status') }}</div>
@endif
@if (Session::has('success'))
<div style="float:right" class="alert-success">{!! Session::get('success') !!}</div>
<script>window.setTimeout(function(){window.location.reload();}, 1000);</script>
@endif
@if (Session::has('failure'))
<div  style="float:right" class="alert-danger">{!! Session::get('failure') !!}</div>
@endif
</div>
				<form method="POST" action="{{ route('user-password-save') }}">
					@csrf


					
<div class="form-group">
	<label for="oldpassword" class="form-label">{{ __('Old Password') }}</label>
	<div class="form-input">
		<input id="oldpassword" type="password" class="form-inputinput @error('oldpassword') is-invalid @enderror" name="oldpassword" required autofocus>
		@error('oldpassword')
		<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
		@enderror
	</div>
</div>

<div class="form-group">
	<label for="password" class="form-label">{{ __('Password') }}</label>
	<div class="form-input">
		<input id="password" type="password" class="form-inputinput @error('password') is-invalid @enderror" name="password" required>
		@error('password')
		<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
		@enderror
	</div>
</div>

<div class="form-group">
	<label for="passwordconfirm" class="form-label">{{ __('Confirm Password') }}</label>
	<div class="form-input">
		<input id="passwordconfirm" type="password" class="form-inputinput @error('passwordconfirm') is-invalid @enderror" name="passwordconfirm" required>
		@error('password')
		<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
		@enderror
	</div>
</div>

<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit" class="btn-primary">{{ __('Submit') }}</button>
	</div>
</div>
                </form>
        
        </div>
    </div>
</div>
@endsection

