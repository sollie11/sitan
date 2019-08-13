<div class="alert"><?php $bMsg = 0; ?>

@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-session">{{ session('status') }}</div> @endif
@if (session('resent'))<?php $bMsg = 1; ?>
<div class="alert-success">{{ __('A fresh verification link has been sent to your email address.') }}</div> @endif				


@if (Session::has('success'))<?php $bMsg = 1; ?>
<div class="alert-success">{!! Session::get('success') !!}</div> @endif
@if (Session::has('failure'))<?php $bMsg = 1; ?>
<div class="alert-danger">{!! Session::get('failure') !!}</div> @endif


@if ($errors->has('business_name'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('business_name') }}</div> @endif
@if ($errors->has('first_name'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('first_name') }}</div> @endif
@if ($errors->has('name'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('name') }}</div> @endif
@if ($errors->has('send_email'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('send_email') }}</div> @endif
@if ($errors->has('surname'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('surname') }}</div> @endif


@error('email')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
@error('name')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
@error('oldpassword')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
@error('password')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
@error('password_confirmation')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
				
				
				
@if ( $bMsg == 1)<script>window.setTimeout(function(){ var eA = document.getElementsByClassName("alert")[0]; eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script> @endif 
</div>