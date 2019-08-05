@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Uploaded') }} {{ $oData['sSingle'] }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-danger">{{ session('status') }}</div> @enderror




@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>



















			<div class="card-body">
				<p>You have uploaded a new {{ $oData['sAction'] }} spreadsheet.</p>
				<p><strong>{{ $oData['iNumRecords'] }}</strong> {{ $oData['sSingle'] }}  were uploaded. Click to inspect the data or just import directly.</p>
				<br><br>
				<a style="margin-right: 2em;" href="../{{ $oData['sAction'] }}/new"><button class="btn-primary">View new {{ $oData['sAction'] }}</button>                   
				<a href="../{{ $oData['sAction'] }}/new/import"><button class="btn-primary">Import new {{ $oData['sAction'] }}</button>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
