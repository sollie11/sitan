@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('New Client Edit') }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
<div id="formdelete">				
<button onclick="JSall.deleterecord('new client', 'clients/new', {{ $oData['oNewClient']->id }})">Delete</button></a>
</div>
<br><br>
<div class="form-group">
	<div style="float: left">Use this form to edit the new client.</div>
	<div class="newline"></div>
</div>
					<form action="{{ route('clients-edit-save') }}" method="post" role="form" class="form-horizontal">
						@csrf
                        
<input id="id" type="hidden" class="form-control" name="id" value="{{ $oData['oNewClient']->id }}">
                        
                        
<div class="form-group">
	<label for="email" class="form-label">Email Address</label>
	<div class="form-input">
		<input id="email" type="text" class="form-inputinput" name="email" value="{{ $oData['oNewClient']->email }}">
	</div>
</div>
						
<div class="form-group">
	<label for="first_name" class="form-label">Name</label>
	<div class="form-input">
		<input id="first_name" type="text" class="form-inputinput" name="first_name" value="{{ $oData['oNewClient']->first_name }}">
	</div>
</div>
						
<div class="form-group">
	<label for="surname" class="form-label">Surname</label>
	<div class="form-input">
		<input id="surname" type="text" class="form-inputinput" name="surname" value="{{ $oData['oNewClient']->surname }}">
	</div>
</div>
						
<div class="form-group">
	<label for="programme" class="form-label">Programme</label>
	<div class="form-input">
		<select id="programme" class="form-inputinput" name="programme">
		@foreach ($oData['aProgrammes'] as $oRec)
			<option value="{{ $oRec->id }}" @if ($oRec->description == $oData['oNewClient']->programme) selected @endif>{{ $oRec->description }}</option>
		@endforeach
		</select>
	</div>
</div>
						
<div class="form-group">
	<label for="questionnaire" class="form-label">Questionnaire</label>
	<div class="form-input">
		<select id="questionnaire" class="form-inputinput" name="questionnaire">
		@foreach ($oData['aQuestionnaires'] as $oRec)
			<option value="{{ $oRec->id }}" @if ($oRec->description == $oData['oNewClient']->questionnaire) selected @endif>{{ $oRec->description }}</option>
		@endforeach
		</select>
	</div>
</div>
						
<div class="form-group">
	<label class="form-label">&nbsp;</label>
	<div class="form-input">
		<button type="submit">{{ __('Save') }}</button>
	</div>
</div>
                </form>
        	</div>
    	</div>
	</div>
</div>
@endsection

