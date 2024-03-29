@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Client Edit') }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
<div id="formdelete" style="float: left">
<button onclick="JSall.deleterecord('client', 'clients', {{ $oData['oClient']->id }})">Delete</button></a>
</div>
@if ($oData['oClient']->form_submitted_at)
<a href="/clients/results/{{ $oData['oClient']->id }}"><button>Questionnaire</button></a>
@endif
<br><br>
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
			<option value="{{ $oRec->id }}" @if ($oRec->id == $oData['oClient']->programme_id) selected @endif>{{ $oRec->description }}</option>
		@endforeach
		</select>
	</div>
</div>
						
<div class="form-group">
	<label for="questionnaire" class="form-label">Questionnaire</label>
	<div class="form-input">
		<select id="questionnaire" class="form-inputinput" name="questionnaire">
		@foreach ($oData['aQuestionnaires'] as $oRec)
			<option value="{{ $oRec->id }}" @if ($oRec->id == $oData['oClient']->active_questionnaire_id) selected @endif>{{ $oRec->description }}</option>
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

