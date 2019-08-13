@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="mainheadersubmenu" id="mainheader">
                		<div style="float: left;">Please confirm submission of your form.</div>
                		<div style="float: right;">{{ session()->get('oUser')['sBusinessName'] }}</div>
                	</div>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
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
