@extends('layouts.app')
@section('content')
<<style>
.upload-btn-wrapper {
    position: relative;
    overflow: hidden;
    display: inline-block;
    width: 200px;
}
.upload-btn {
    border: 1px solid #888888;
    color: #000000;
    background-color: #eeeeee;
    padding: 4px 20px;
    border-radius: 3px;
    font-size: 14px;
    width: 120px;
}
.upload-btn-wrapper input[type=file] {
    font-size: 50px;
    position: absolute;
    left: 0;
    top: 0;
    opacity: 0;
}
progress.upload{
    height: 25px;
    width: 100%;
}
.uploadfilesize{
    position: absolute;
    margin-left: 10px;
    line-height: 0;
}
table.upload {
    border-spacing: 4px;
    border-left: 1px solid #d8d8d8;
    border-collapse: separate;

}
</style>
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Upload ') }} {{ $oData['sAction'] }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-success">{{ session('status') }}</div> @endif




@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>



















			<div class="card-body">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
window.onload = function(){
	JSupload.uploadform("card-body", uploadSuccess, 
		"Upload the spreadsheet containing the new {{ $oData['sSingle'] }} records.", 
		"{{ $oData['sAction'] }}", 
		"csv,xls,xlsx");
};
function uploadSuccess(sResponse){
	var sRoute;
	sRoute = "/{{ $oData['sAction'] }}/upload/new/" + sResponse;
	document.location.href = sRoute;
}
</script>
@endsection
