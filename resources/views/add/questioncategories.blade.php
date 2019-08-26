@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Category Add') }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
<div class="form-group">
	<div style="float: left">Use this form to add a question category.</div>
	<div class="newline"></div>
</div>
					<form action="{{ route('questions-categories-add-save') }}" method="post" role="form" class="form-horizontal">
						@csrf
                        
<div class="form-group">
	<label for="description" class="form-label">Description</label>
	<div class="form-input">
		<input id="description" type="text" class="form-inputinput" name="description">
	</div>
</div>
						
<div class="form-group">
	<label for="graph_description" class="form-label">Graph description</label>
	<div class="form-input">
		<input id="graph_description" type="text" class="form-inputinput" name="graph_description">
	</div>
</div>
						
<div class="form-group">
	<label for="information" class="form-label">Information</label>
	<div class="form-input">
		<textarea rows="4" id="information" name="information" style="margin-bottom: 1em; width: 100%;"></textarea>
	</div>
</div>
						
<div class="form-group">
	<label for="index_no" class="form-label">Index No</label>
	<div class="form-input">
		<input id="index_no" type="text" class="form-inputinput" name="index_no">
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

