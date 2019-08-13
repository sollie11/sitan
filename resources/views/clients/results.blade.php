@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header">
				<div class="card-header-title">Client Form Answers: {{ $oData['sBusinessName'] }}</div>
				@include('alerts')
			</div>









				<div class="card-body">
				
				<a href="/clients/download/{{ $oData['iUserID'] }}"><button>Download Spreadsheet</button></a>
				<br><br>            
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<div style="height:400px;">
@include('formgraph', array('oData' => $oData))
</div>
<div style="margin-top:30px;">
<table border="0" style="border:0.02em solid #2c5aa0; width:100%; text-align: left;">
<tr>
<th style="width: 20%">Category</th>
<th style="width: 35%">Question</th>
<th style="width: 37%">Answer</th>
<th style="width: 8%;">Percentage</th>
</tr>
<?php

$sCat = '';
$aData = $oData['aAnswers'];
?>
@foreach ($aData as $oRec)
<tr>
<?php 
if ($sCat == $oRec->category){
	$oRec->category = '';
} else {
	$sCat = $oRec->category;
}?>
<td>{{ $oRec->category }}</td>
<td>{{ $oRec->question }}</td>
<td>{{ $oRec->optiona }}</td>
<td style="text-align: center;">{{ (intval($oRec->real_score * 10) / 10) }}</td>
</tr>
@endforeach
</table>
</div>
<div style="margin-top:30px;">
<table border="1" style="border:1px solid #2c5aa0; width:100%">
<tr>
<th style="width: 20%">Comments</th><th style="width:80%"></th> </tr>
@foreach ($aData as $oRec)
	@if ($oRec->option_id == 1)
<tr><td>{{ $oRec->category }}</td><td>{{ $oRec->comment }}</td></tr>
	@endif
@endforeach
</table>
</div>




        </div>
    </div>
</div>
@endsection


