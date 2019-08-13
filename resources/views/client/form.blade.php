@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header" style="height: 6em;"><?php 
$iI = 0;
$iF = 0;
while (($iI < sizeof($oData['aCats'])) && (!$iF)){
	if ($oData['aCats'][$iI]->index_no == $oData['iPageNo']){
		$iF = $iI + 1;
	} else {
		$iI++;
	}
}
if ($iF){
	$iF--;
}
?>
				<div class="card-header-title">{{ ($iF + 1) }}. {{ $oData['aCats'][$iF]->description }}</div>
				<div class="card-header-info">{{ $oData['aCats'][$iF]->information }}</div>
				@include('alerts')
			</div>
			<div class="card-body">
<table border=0 width="100%" style="border: 0px solid #cccccc">
<tr><td width="3%"></td><td width="35%"></td><td width="60%"></td></tr>
<?php $iI = 1;?>
@foreach ($oData['aQuestions'] as $oRec)
<tr class="trline"><td>{{ $iI }}.</td><td>{{ $oRec->description }}</td><td>
<select class="s100" id="form_{{ $oData['iCatID'] }}_{{ $oRec->id }}">
<option value="0"></option>
@foreach ($oData['aOptions'] as $oRec1)
@if ($oRec1->question_id == $oRec->id)
<option value="{{ $oRec1->id }}"<?php
foreach ($oData['aAnswers'] as $oRec2){
	if (($oRec2->question_id == $oRec->id) && ($oRec2->option_id == $oRec1->id)){
		echo ' selected';
	}
}
?>>{{ $oRec1->description }}</option>
@endif
@endforeach
</select>
</td></tr>
<?php $iI++; ?>
@endforeach
<tr><td colspan="3">
<div style="margin-top:1em; width: 60%;">
<textarea rows="5" style="width: 100%" placeholder="Enter your comments here for this category"></textarea>
</div>
</td></tr>
</table>
			</div>
		</div>
    </div>
</div>
<script>
console.log("formblade");
window.setTimeout(function(){
	var aE, eX;
	aE = JSall.gcn("s100");
	aE.forEach(function(eX){
		eX.onchange = JSform.formoption;
	});
}, 1000);
</script>
@endsection
