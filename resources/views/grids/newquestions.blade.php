@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header">
				<div class="card-header-title">{{ __('New questions') }}</div>
<?php 
$aData = $oData['aData'];
$oPaging = $oData['oPaging'];
$aColumns = $oData['aColumns'];
$oSort = $oData['oSort'];
?>
				@include('grid', array('iPages' => $oPaging['iPages'], 'aButton' => array('new/import', 'Import')))
				@include('alerts')
			</div>












			<div class="card-body grid">
    <table class="gridtable">
	    <tr style="cursor: pointer">
<?php $iI = 0;?>
@foreach ($aColumns as $aRec)
	<th id="gridheading_{{ $iI }}" class="gridheading" style="width: {{ $aRec[0] }}%">
		<div style="float: left;">{{ $aRec[1] }}</div>
		<div id="gridheadingsort_{{ $iI }}" style="float: right; padding-right: 10px;" <?php
$sClass = 'class="sort_0">';
if (($iI + 1) == $oSort['iColumn']){ 
	if ($oSort['iDirection'] == 2){ 
		$sClass = ' class="sort_0">&#x25B2;';
	} else {
		$sClass = ' class="sort_1">&#x25BC;';
	}
}
echo $sClass;?>
</div>
	</th>
<?php $iI++;?>
@endforeach
</tr>
<?php 
$iRecStart = ($oPaging['iPageNo'] - 1) * $oPaging['iResultsPerPage'];
$iRecStop = $iRecStart + $oPaging['iResultsPerPage'];
$iRowColor = 0;
?>
@for ($iRowNo = $iRecStart; $iRowNo < $iRecStop; $iRowNo++)
<?php 
if (isset($aData[$iRowNo])) {
	$oRec = $aData[$iRowNo];
} else {
	$oRec = 0;
}
?>
@if ($oRec)
	<tr id="newqs_{{ $oRec->id }}" class="trgrid"<?php 
	if (!($iRowColor % 2)){
		echo 'style="background: #e4fcef;"';
	}
	$iRowColor++;
	?>>
	<td>{{ $oRec->id }}</td><td>{{ $oRec->questionnaire }}</td><td>{{ $oRec->category }}</td><td>{{ $oRec->question }}</td><td>{{ $oRec->options }}</td><td>{{ $oRec->score }}</td>
	</tr>
@endif
@endfor
    </table>
</div>
@endsection
