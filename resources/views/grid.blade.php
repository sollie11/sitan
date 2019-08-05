<?php 
if (isset($_GET['page'])){
	$iPageNo = intval($_GET['page']);
	if (!$iPageNo){
		$iPageNo = 1;
	}
} else {
	$iPageNo = 1;
}
if (isset($_GET['search'])){
	$sSearch = $_GET['search'];
} else {
	$sSearch = '';
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
<a href="{{ $aButton[0] }}"><button class="btn-primary grid">{{ $aButton[1] }}</button></a>
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


