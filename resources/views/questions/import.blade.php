@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Questions import') }}</div>
				@include('alerts')
			</div>














			<div class="card-body">

<div class="form-group">
	<div class="gridcontrols">
		<button onclick="JSnewquestions.importnow()">Import Now</button>
		<a href="."><button>View new questions</button></a>				
	</div>
</div>
<div class="form-group">
	<div style="float: left">Import the new questions here. After this process, these new questions can be assigned to questionnaires.</div>
	<div class="newline"></div>
</div>
                <form action="{{ route('questions-new-import-save') }}" method="post" role="form" class="form-horizontal">
                    @csrf

                        
			
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
var JSnewquestions = {
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

