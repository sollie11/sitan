@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Questionnaire Edit') }}</div>
				@include('alerts')
			</div>








				<div class="card-body">
<div id="formdelete">
<button onclick="JSall.deleterecord('questionnaire', 'questions/questionnaires', {{ $oData['oRec']->id }})">Delete</button></a>
</div>
<br><br>
<div class="form-group">
	<div style="float: left">Use this form to edit the questionnaire.</div>
	<div class="newline"></div>
</div>
					<form action="{{ route('clients-edit-save') }}" method="post" role="form" class="form-horizontal">
						@csrf
                        
<input id="id" type="hidden" class="form-control" name="id" value="{{ $oData['oRec']->id }}">
                        
					
<div class="form-group">
	<label for="description" class="form-label">Description</label>
	<div class="form-input">
		<input type="text" id="description" class="form-inputinput" name="description" value="{{ $oData['oRec']->description }}">
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

