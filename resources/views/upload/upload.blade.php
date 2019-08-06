@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Upload ') }} {{ $oData['sAction'] }}</div>
				@include('alerts')
			</div>



















			<div class="card-body">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
console.log("upload");
window.setTimeout(function(){
	JSupload.uploadform("card-body", uploadSuccess, 
		"Upload the spreadsheet containing the new {{ $oData['sSingle'] }} records.", 
		"{{ $oData['sAction'] }}", 
		"csv,xls,xlsx");
}, 1000);
function uploadSuccess(sResponse){
	var sRoute;
	sRoute = "/{{ $oData['sAction'] }}/upload/new/" + sResponse;
	document.location.href = sRoute;
}
</script>
@endsection
