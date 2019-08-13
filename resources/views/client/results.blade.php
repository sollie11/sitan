@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header"> 
				<div class="card-header-title">Results</div>
				@include('alerts')
			</div>
			<div class="card-body">
<div class="results-graph">
<svg width="700" height="570" viewBox="0 0 950 800" xmlns="http://www.w3.org/2000/svg">
  <g style="font-size:16px;" xml:space="preserve">
@for ($iI = 0; $iI < 6; $iI++)
    <text y="{{ $iI * 100 + 20 }}" x="9"><tspan>{{ 100 - $iI * 20 }}</tspan></text>
@endfor
  </g>
  <g style="font-size:13px;" xml:space="preserve">
@for ($iI = 0; $iI < 9; $iI++)
  <text x="{{ 60 + (100 * $iI) }}"  y="540">
  <tspan>{{ $oData['aCategories'][$iI]->graph_description }}</tspan></text>
@endfor
  </g>
  <g style="stroke:#cccccc;">
  <path d="M 40,515 H 970" />
  <path d="M 40,415 H 970" />
  <path d="M 40,315 H 970" />
  <path d="M 40,215 H 970" />
  <path d="M 40,115 H 970" />
  <path d="M 40,15  H 970" />
  </g>
    <path style="fill:#009873;" id="graph"
     d="m 40,520 v -5 H 60 @foreach ($oData['aCategories'] as $oRec) V {{ (515 - (5 * $oRec->total)) }} h 80 v {{ (5 * $oRec->total) }} h 20 @endforeach v 5 z" />
</svg>
</div>
<div class="results-stats">
<span class="results-header">Summary of scores</span>
<table border=0 width="100%" style="font-size: 1.0em;">
<tr><td><strong>Category</strong></td><td><strong>Percent</strong></td></tr>
<?php $iTot = 0;?>
@foreach ($oData['aCategories'] as $oRec)
<tr><td>{{ $oRec->description }}</td><td>{{ intval($oRec->total) }}</td></tr>
<?php $iTot += $oRec->total;?>
@endforeach
<tr><td></td><td></td><td></td></tr>
<tr><td><span class="results-header">Total</span></td><td><span class="results-header">{{ intval($iTot / 9 * 10) / 10 }}%</span></td></tr>
</table>
</div>
</div>
<div class="results-buttons">
<div style="float: left;"><a href="/form/submit"><button>Submit Form</button></a></div>
<div><a href="/form/page/1"><button>Back to form</button></a></div>
</div>
@endsection
