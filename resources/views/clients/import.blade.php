@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Clients import') }}</div>
				@include('alerts')
			</div>














			<div class="card-body">

<div class="form-group">
	<div class="gridcontrols">
		<button onclick="JSnewclients.importnow()">Import Now</button>
		<a href="."><button>View new clients</button></a>				
	</div>
</div>
<div class="form-group">
	<div style="float: left">Import the new clients here. After this process, they will be able to log in and fill in their questionnaire. Please choose the email options below.</div>
	<div class="newline"></div>
</div>
                <form action="{{ route('clients-new-import-save') }}" method="post" role="form" class="form-horizontal">
                    @csrf

                        
                         
<div class="form-group">
	<label for="send_email" class="form-label">{{ __('Send email') }}</label>
	<div class="form-input">
		<input id="send_email" style="width: 20px;" type="checkbox" class="form-inputcheck" name="send_email">
	</div>
</div>

<div class="form-group">
	<label for="email_form" class="form-label">{{ __('Email form to use') }}</label>
	<div class="form-input">
		<select onchange ="JSnewclients.emailform()" id="email_form" class="form-inputselect" name="email_form">
		@foreach ($oData['aEmailForms'] as $oQ)
			<option value="{{ $oQ->id }}">{{ $oQ->description }}</option>
		@endforeach
		</select>
	</div>
</div>

<div class="form-group">
	<label for="example" class="form-label">{{ __('Email Example') }}</label>
	<div class="form-input">
		<div style="height: auto;" id="example" class="form-info" name="example"></div>
	</div>
</div>
						
<div class="form-group" style="display: none">
	<button id="ncsubmit" type="submit">Submit</button>
</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
console.log("importjs");
var aEmailForms = {!! json_encode($oData['aEmailForms'], 1) !!};
var JSnewclients = {
emailform: function(){
	var eSel, eText, sFound;
	eSel = JSgrid.dg("email_form");
	if (!eSel){
		return;
	}
	sFound = "";
	aEmailForms.forEach(function(oRec){
		if (oRec.id == eSel.value){
			sFound = oRec.contents;
		}
	});
	if (sFound){
		eText = JSgrid.dg("example");
		sFound = sFound.replace(/\[\*/g, "<span style='font-weight: bold'><b>");
		sFound = sFound.replace(/\*\]/g, "</b></span>");
		eText.innerHTML = sFound;
	}
},

importnow: function(){
	var eSub;
	eSub = JSgrid.dg("ncsubmit");
	if (eSub){
		eSub.click();
	}
},

};

</script>
@endsection

