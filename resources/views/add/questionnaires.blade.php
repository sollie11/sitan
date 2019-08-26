@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Questionnaire Add') }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
<div class="form-group">
	<div style="float: left">Use this form to add a questionnaire.</div>
	<div class="newline"></div>
</div>
	                    
				<form action="{{ route('questions-questionnaires-add-save') }}" method="post" role="form" class="form-horizontal">
                   @csrf
                        
<div class="form-group">
	<label for="description" class="form-label">Description</label>
	<div class="form-input">
		<input id="description" type="text" class="form-inputinput" name="description">
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
@endsection

