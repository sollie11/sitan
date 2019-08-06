@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Personal details') }}</div>
@include('alerts')				
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
		<button type="submit">{{ __('Change personal details') }}</button>
	</div>
</div>
                </form>
        	</div>
    	</div>
	</div>
</div>
@endsection

