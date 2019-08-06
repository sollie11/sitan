@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Imported') }} {{ $oData['sSingle'] }}</div>
				<div class="alert"><?php $bMsg = 0; ?>
@if (session('status'))<?php $bMsg = 1; ?>
<div class="alert-session">{{ session('status') }}</div> @enderror
@if (session('success'))<?php $bMsg = 1; ?>
<div class="alert-success">{{ session('success') }}</div> @enderror




@if ( $bMsg == 1)
	<script>window.setTimeout(function(){var eA = document.getElementsByClassName("alert")[0]; 
	eA.title = eA.children[0].innerHTML; eA.innerHTML=".&nbsp;";}, 3000);</script>
@endif
				</div>
			</div>



















			<div class="card-body">
				<p>You have imported <strong>{{ $oData['iTotal'] }}</strong> {{ $oData['sSingle'] }}.</p>
				<p><strong>{{ $oData['iDeleted'] }}</strong> {{ $oData['sSingle'] }}  were overwritten. Click to view the {{ $oData['sSingle'] }}.</p>
				<p>You now have <strong>{{ $oData['iNumQuestions'] }}</strong> {{ $oData['sSingle'] }} in the database.</p>
				<br><br>
				<a href="../{{ $oData['sSingle'] }}"><button>View {{ $oData['sSingle'] }}</button>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
