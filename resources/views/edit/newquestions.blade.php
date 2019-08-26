@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('New Question Edit') }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
<div id="formdelete">				
<button onclick="JSall.deleterecord('new question', 'questions/new', {{ $oData['oRec']->id }})">Delete</button></a>
</div>
<br><br>
<div class="form-group">
	<div style="float: left">Use this form to edit the new question.</div>
	<div class="newline"></div>
</div>
					<form action="{{ route('questions-new-edit-save') }}" method="post" role="form" class="form-horizontal">
						@csrf
                        
<input id="id" type="hidden" class="form-control" name="id" value="{{ $oData['oRec']->id }}">
                        
					
<div class="form-group">
	<label for="questionnaire" class="form-label">Questionnaire</label>
	<div class="form-input">
		<select id="questionnaire" class="form-inputinput" name="questionnaire">
		@foreach ($oData['aQuestionnaires'] as $oRec)
			<option value="{{ $oRec->id }}" @if ($oRec->description == $oData['oRec']->questionnaire) selected @endif>{{ $oRec->description }}</option>
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

