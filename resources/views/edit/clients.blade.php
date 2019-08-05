@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Client Edit') }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@error('email')<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $message }}</div> @enderror
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ session('status') }}</div> @endif
@if (Session::has('success'))<?php $bMsg = 1; ?>
<div class="alert-danger">{!! Session::get('success') !!}</div> @endif
@if (Session::has('failure'))<?php $bMsg = 1; ?>
<div class="alert-danger">{!! Session::get('failure') !!}</div> @endif
@if ($errors->has('name'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('name') }}</div> @endif
@if ($errors->has('surname'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('surname') }}</div> @endif




@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>









				<div class="card-body">
<div class="form-group">
	<div style="float: left">Use this form to edit the user.</div>
	<div class="newline"></div>
</div>
					<form action="{{ route('clients-edit-save') }}" method="post" role="form" class="form-horizontal">
						@csrf
                        
<input id="id" type="hidden" class="form-control" name="id" value="{{ $oData['oClient']->id }}">
                        
                        
<div class="form-group">
	<label for="email" class="form-label">Email Address</label>
	<div class="form-input">
		<input id="email" type="text" class="form-inputinput" name="email" value="{{ $oData['oClient']->email }}">
	</div>
</div>
						
<div class="form-group">
	<label for="name" class="form-label">Name</label>
	<div class="form-input">
		<input id="name" type="text" class="form-inputinput" name="name" value="{{ $oData['oClient']->name }}">
	</div>
</div>
						
<div class="form-group">
	<label for="surname" class="form-label">Surname</label>
	<div class="form-input">
		<input id="surname" type="text" class="form-inputinput" name="surname" value="{{ $oData['oClient']->surname }}">
	</div>
</div>
						
<div class="form-group">
	<label for="programme" class="form-label">Programme</label>
	<div class="form-input">
		<select id="programme" class="form-inputinput" name="programme">
		@foreach ($oData['aProgrammes'] as $oRec)
			<option value="{{ $oRec->id }}" @if ($oRec->id == $oData['oClient']->programme_id) selected (@endif)</option>
		@endforeach
		</select>
	</div>
</div>
						
<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit" class="btn-primary">{{ __('Save') }}</button>
	</div>
</div>
                </form>
        	</div>
    	</div>
	</div>
</div>
@endsection

