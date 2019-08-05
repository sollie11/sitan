@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Verify your email address') }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('resent'))<?php $bMsg = 1; ?>
<div class="alert-success">{{ __('A fresh verification link has been sent to your email address.') }}</div> @endif				
@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>
			
			<div class="card-body">
				<span>{{ __('Before proceeding, please check your email for a verification link.') }}</span>
				<span>{{ __('If you did not receive the email') }}, <a class="colornodecor" href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</span>
			</div>
		</div>
	</div>
</div>
@endsection
