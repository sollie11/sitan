@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header">
				<div class="card-header-title">{{ env('APP_NAME') }}</div>
				@include('alerts')
			</div>
			<div class="card-body">
				<p>Welcome to the <span style="font-weight: bold">{{ $oData['sQuestionnaire'] }}</span> questionnaire. You are using the <span style="font-weight: bold">{{ $oData['sProgramme'] }}</span> programme.</p>
				<p>You are about to complete a questionnaire which is intended as an honest, accurate self-assessment of where the current strengths and weaknesses lie in your organisation, for your own benefit.</p>
				<p>The categories start at <span style="font-weight: bold">{{ $oData['sCategoryFirst'] }}</span> and end at <span style="font-weight: bold">{{ $oData['sCategoryLast'] }}</span>. Once you have completed all <span style="font-weight: bold">{{ $oData['iQuestionsCount'] }}</span> questions in <span style="font-weight: bold">{{ $oData['iCategoriesCount'] }}</span> categories, submit the form to see the results. The graphs will give clear indications of where you need to apply your attention. And you'll help us to help you!<br></p>
				<a href="/form/page/<?php
$iPage = 1;
if (isset($_COOKIE['sitan_pageno'])){
	$iPage = $_COOKIE['sitan_pageno'];
}
if (!$iPage){
	$iPage = 1;
}
echo $iPage;
				?>"><button>Questionnaire</button>
			</div>
		</div>
    </div>
</div>
@endsection
