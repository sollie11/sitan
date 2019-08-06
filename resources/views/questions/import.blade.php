@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Questions import') }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-session">{{ session('status') }}</div> @endif
@if (Session::has('success'))<?php $bMsg = 1; ?>
<div class="alert-success">{!! Session::get('success') !!}</div> @endif
@if (Session::has('failure'))<?php $bMsg = 1; ?>
<div class="alert-danger">{!! Session::get('failure') !!}</div> @endif
@if ($errors->has('send_email'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ $errors->first('send_email') }}</div> @endif

@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
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

