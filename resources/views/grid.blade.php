<?php 
function getGET($sGet){
	$aGet1 = base64_decode($sGet);
	if (!isset($aGet1)){
		return array();
	}
	$aGet1 = explode('&', $aGet1);
	$aGet = array();
	foreach ($aGet1 as $aRec){
		$aRec = explode('=', $aRec);
		$aGet[$aRec[0]] = $aRec[1];
	}
	return $aGet;
}

$iPageNo = 1;
$sSearch = '';
if (isset($_GET['grid'])){
	$aGet = getGET($_GET['grid']);
	if (isset($aGet['page'])){
		$iPageNo = intval($aGet['page']);
		if (!$iPageNo){
			$iPageNo = 1;
		}
	}
	if (isset($aGet['search'])){
		$sSearch = $aGet['search'];
	}
}
/*
<td style="width: 20%"><button onclick="window.location.href='export';" class="grid">Export</button></td>
*/
?>
<div class="gridcontrols">

@if (isset($sAdd))
<a href="{{ $sAdd }}/add"><button class="gridadd">Add</button></a>
@endif
@if (isset($aButton))
<a href="{{ $aButton[0] }}"><button class="grid">{{ $aButton[1] }}</button></a>
@endif
	<table border=0 class="gridcontrolstable">
		<tr style="font-size: 14px; text-align: center;">
			
			<td onclick="JSgrid.page('first');"><<</td>
			<td onclick="JSgrid.page('previous')"><</td>
			<td>{{ $iPageNo }} of {{ $iPages }}</td>
			<td onclick="JSgrid.page('next', {{ $iPages }});">></td>
			<td onclick="JSgrid.page('last', {{ $iPages }});">>></td>
			<td style="width: 40%">
				<input value="{{ $sSearch }}" placeholder="Search" onkeyup="return JSgrid.search();" style="width:100%" id="gridfilter" />
			</td>
			<td>
				<div onclick="JSgrid.searchclear();" style="font-size: 20px; margin-left: -25px; width: 25px;">X</div>
			</td>
		</tr>
	</table>
</div>


