@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Personal details') }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-success">{{ session('status') }}</div> @endif
@if (Session::has('success'))<?php $bMsg = 1; ?>
<div class="alert-success">{!! Session::get('success') !!}</div> @endif
@if (Session::has('success'))<?php $bMsg = 1; ?>
<div class="alert-danger">{!! Session::get('failure') !!}</div> @endif
@if ($errors->has('surname'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('surname') }}</div> @endif
@if ($errors->has('business_name'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('business_name') }}</div> @endif


@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>



















			<div class="card-body">
<div class="form-group">
	<div style="float: left">Use this form to change your personal details.</div>
	<div class="newline"></div>
</div>
				<form action="{{ route('user-personaldetails-save') }}" method="post" role="form" class="form-horizontal">
                    @csrf



<div class="form-group">
	<label for="first_name" class="form-label">{{ __('First Name') }}</label>
	<div class="form-input">
		<input id="first_name" type="text" class="form-inputinput" name="first_name" value="{{ Auth::user()->name }}">
	</div>
</div>

<div class="form-group">
	<label for="surname" class="form-label">{{ __('Surname') }}</label>
	<div class="form-input">
		<input id="surname" type="text" class="form-inputinput" name="surname" value="{{ Auth::user()->surname }}">
	</div>
</div>

<div class="form-group">
	<label for="business_name" class="form-label">{{ __('Business Name') }}</label>
	<div class="form-input">
		<input id="business_name" type="text" class="form-inputinput" name="business_name" value="{{ Auth::user()->business_name }}">
	</div>
</div>

<div class="form-group">
	<div class="form-inputright">
		<button type="submit" class="btn-primary">{{ __('Change personal details') }}</button>
	</div>
</div>
                </form>
        	</div>
    	</div>
	</div>
</div>
@endsection

