@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Change Password') }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
	<div class="alert-session" role="alert">{{ session('status') }}</div> @endif
@if (Session::has('success'))<?php $bMsg = 1; ?>
	<div class="alert-success">{!! Session::get('success') !!}</div> @endif
@if (Session::has('failure'))<?php $bMsg = 1; ?>
	<div class="alert-danger">{!! Session::get('failure') !!}</div> @endif
@error('oldpassword')<?php $bMsg = 1; ?>
	<div class="alert-danger">{{ $message }}</div> @enderror
@error('password')<?php $bMsg = 1; ?>
	<div class="alert-danger">{{ $message }}</div> @enderror
@error('password_confirmation')<?php $bMsg = 1; ?>
	<div class="alert-danger">{{ $message }}</div> @enderror
@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
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

