<div style="border:1px solid #007398; width: 730px; height: 400px; float: left">
<svg width="700" height="570" viewBox="0 0 950 800" xmlns="http://www.w3.org/2000/svg">
  <g style="font-size:16px;" xml:space="preserve">
@for ($iI = 0; $iI < 6; $iI++)
    <text y="{{ $iI * 100 + 20 }}" x="9"><tspan>{{ 100 - $iI * 20 }}</tspan></text>
@endfor
  </g>
  <g style="font-size:13px;" xml:space="preserve">
@for ($iI = 0; $iI < 9; $iI++)
  <text x="{{ 70 + (100 * $iI) }}"  y="540">
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
    <path style="fill:#2c5aa0;" id="graph"
     d="m 40,520 v -5 H 60 @foreach ($oData['aCategories'] as $oRec) V {{ (515 - (5 * $oRec->total)) }} h 80 v {{ (5 * $oRec->total) }} h 20 @endforeach v 5 z" />
</svg>
</div>
<div style="padding: 5px; border:1px solid #007398; width: 305px; height: 400px; float: right">
<span class="bolder">Summary of scores</span>
<table border=0 width="100%" style="text-align: left;">
<tr><th>Category</th><th>Percent</th></tr>
<?php $iTot = 0;?>
@foreach ($oData['aCategories'] as $oRec)
<tr><td>{{ $oRec->description }}</td><td>{{ intval($oRec->total) }}</td></tr>
<?php $iTot += $oRec->total;?>
@endforeach
<tr><td></td><td></td><td></td></tr>
<tr><td><span class="bolder">Total</span></td><td><span class="bolder">{{ intval($iTot / 9 * 10) / 10 }}%</span></td></tr>
</table>
</div>
