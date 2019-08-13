@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header" style="height: 6em;">
				<div class="card-header-title">{{ __('Submit questionnaire') }}</div>
				@include('alerts')
			</div>
			<div class="card-body">
<?php //<p>You answered <b>{{ $oData['iAnswers'] }}</b> from a total of <b>{{ $oData['iTotalAnswers'] }}</b> questions. You entered comments on <b>{{ $oData['iComments'] }}</b> of <b>{{ $oData['iTotalComments'] }}</b> pages. Overall, you completed <b>{{ intval(($oData['iAnswers'] + $oData['iComments']) / ($oData['iTotalAnswers'] + $oData['iTotalComments']) * 100) }}%</b> of the questionnaire.
?><br>
@include('formgraph', array('oData' => $oData))

<div style="float: left; width: 40%; padding-top:20px;">
<table style="width: 100%">
<tr>
<td><button onclick="window.open('submitted', '_parent');">Submit form</button></td>
<td><button onclick="window.open('form?p=1', '_parent');">Back to form</button></td>
</tr></table></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
