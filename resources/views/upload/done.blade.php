@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Uploaded') }} {{ $oData['sSingle'] }}</div>
				@include('alerts')
			</div>



















			<div class="card-body">
				<p>You have uploaded a new {{ $oData['sAction'] }} spreadsheet.</p>
				<p><strong>{{ $oData['iNumRecords'] }}</strong> {{ $oData['sSingle'] }}  were uploaded. Click to inspect the data or just import directly.</p>
				<br><br>
				<a href="../{{ $oData['sAction'] }}/new"><button>View new {{ $oData['sAction'] }}</button></a>                   
@if ($oData['sAction'] == 'clients')
				<a href="../{{ $oData['sAction'] }}/new/import"><button>Import new {{ $oData['sAction'] }}</button></a>
@endif
@if ($oData['sAction'] == 'questions')
				<button onclick="document.getElementById('nqsubmit').click();">Import new {{ $oData['sAction'] }}</button></a>
<form action="{{ route('questions-new-import-save') }}" method="post" role="form" class="form-horizontal">
	@csrf
<div class="form-group" style="display: none">
	<button id="nqsubmit" type="submit">Submit</button>
</div>
				</form>
@endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
