<?php
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

$bLoggedIn = 1;
$bIsAdmin = 0;
if (!Auth::check()){
	$bLoggedIn = 0;
}
$oUser = Auth::user();
if (!$oUser){
	$bLoggedIn = 0;
} else {
	if (!$oUser->email_verified_at){
		return;
	}
	$bIsAdmin = 0;
	if ($oUser->is_admin){
		$bIsAdmin = 1;
	}
	if ($oUser->is_client){
		$bIsAdmin = -1;
		$aCats = DB::select('SELECT id, index_no, description, graph_description ' .
			'FROM questions_categories ' .
			'WHERE id IN (SELECT category_id FROM questionnaire_category ' .
			'WHERE questionnaire_id = ?) ORDER BY index_no',
			array(Auth::user()->active_questionnaire_id)
		);
		$iCatFirst = $aCats[0]->index_no;
		$iCatLast = $aCats[sizeof($aCats) - 1]->index_no;
		echo '<script>var iCatFirst = ' . $iCatFirst . '; var iCatLast = ' . $iCatLast . ';</script>';
	}
	$sMenu = 'client';
	if ($bIsAdmin == 1){
		$sMenu = 'admin';
	}
	$bIsFirstLogin = $oUser->is_first_login;
	$iUserID = $oUser->id;
	$sBusinessName = $oUser->business_name;
}
?>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ getenv('APP_NAME') }}</title>
	<script src="{{ asset('js/app2.js') }}" defer></script>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
	<div id="app">
		<nav class="navbar">
			<div class="container">
				<a class="navbar-brand" href="{{ env('GATEWAY_URL') }}">
					<div class="header-brand"><img src="/{{ env('APP_LOGOSMALL') }}" /></div>
					<div class="header-brand">{!! implode('<br>', explode(' ', env('APP_NAME'))) !!}</div>
				</a>
				@guest
				@if (Route::has('register'))
				<ul class="navbar">
					<li class="navbar">
						<a class="toprightlinks" href="{{ route('register') }}">{{ __('Register') }}</a>
					</li>
				@endif
					<li class="navbar">
						<a class="toprightlinks" href="{{ route('login') }}">{{ __('Login') }}</a>
					</li>
				</ul>
			</div>
@else
				
<!-- Logged in -->
				<div class="navuser">
					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf</form>
					<div class="w3navbar">
						<div class="w3ddown">
							<button class="w3dbtn" style="text-align: right;">@if (Auth::user()->name){{ Auth::user()->name }}@else {{ __('User') }} @endif &nbsp;&nbsp;<i class="w3caret"></i></button>
							<div class="w3ddown-content" id="nav_0" menu="user">
<?php $aM = Config::get('app.menuuser'); ?>
				@foreach ($aM as $aMenu)
				<a>{{ $aMenu[0] }}</a>
				@endforeach			</div>
						</div>
					</div>
				</div><?php 
$aM = Config::get('app.menu' . $sMenu);
if ($sMenu == 'client'){
	$aC = array();
	foreach ($aCats as $oRec){
		$aC[] = $oRec->description;
	}
	$sM = $aM[3];
	$aMn = array($sM, $aC);
	$aMn[0] = $aMn[0][0];
	$aM[3] = $aMn;
}
$iI = 1;
foreach ($aM as $aMenu){
	echo  "\n";
	echo '<div class="navmain">' . "\n";
	echo '	<div class="w3navbar">' . "\n";
	echo '		<div class="w3ddown">' . "\n";
	$aA = explode('||', $aMenu[0]);
	if (!isset($aA[1])){
		$aA[1] = str_replace(' ', '', strtolower($aA[0]));
	}
	if (isset($aMenu[1])){
		if ($bIsAdmin == 1){
			$sMenu2 = $aA[1];
		} else {
			$sMenu2 = 'form/page';
		}
		echo '			<button class="w3dbtn">' . $aA[0] . '<i class="w3caret"></i></button>' ."\n";
		echo '			<div id="nav_' . $iI . '" class="w3ddown-content" ' .
			'menu="' . $sMenu2 . '">' ."\n";
		$iJ = 1;
		foreach ($aMenu[1] as $aMenu1){
			$aDest = array($aMenu1);
			$aDest[1] = str_replace(' ', '', strtolower($aMenu1));
			$aDest[2] = explode('||', $aDest[1]);
			if ($bIsAdmin == 1){
				if (isset($aDest[2][1])){
					$sMenu1 = ' menu="' . $aDest[2][1] . '"';
				} else {
					$sMenu1 = '';
				}
			} else {
				$sMenu1 = ' menu="' . ($iJ) . '"';
			}
			$aDest[3] = explode('||', $aDest[0])[0];
			echo '				<a' . $sMenu1 . '>' . $aDest[3] . '</a>' ."\n";
			$iJ++;
		}
		echo '			</div>' ."\n";
	} else {
		echo '			<a id="nav_' . $iI . '" class="w3dbtn client" menu="' . $sMenu . '_' . $aA[1] . 
			'">' . $aA[0] . '</a>' . "\n";
	}
	echo '		</div>' . "\n";
	echo '	</div>' . "\n";
	echo '</div>' . "\n";
	$iI++;
}
?> 
			</div>
		</nav>
		@endguest<div class="newline"></div>
		<main>
@yield('content')
		</main>
	</div>
@if ($bIsAdmin == -1) <script>window.setTimeout(function(){JSform.init();}, 1000);</script> @endif
</body>
</html>
