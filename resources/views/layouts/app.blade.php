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
				<a class="navbar-brand" href="{{ url('/home') }}">
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
				@else
<!-- Logged in -->
<div class="navuser">
	<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> @csrf </form>
	<div class="w3navbar">
	  <div class="w3ddown">
		<button class="w3dbtn" style="text-align: right;" onclick="menu('nav_0')">@if (Auth::user()->name){{ Auth::user()->name }}@else {{ __('User') }} @endif &nbsp;&nbsp;<i class="w3caret"></i>	</button>
		
		<div class="w3ddown-content" id="nav_0">
<?php $aM = Config::get('app.menuuser'); ?>
@foreach ($aM as $aMenu)
			<a onclick="clickmenu('user', '{{ str_replace(' ', '', strtolower($aMenu[0])) }}')">{{ $aMenu[0] }}</a>
@endforeach
		</div>
	</div> 
	</div>
</div>
<script>
console.log("menu");
var iActiveMenu = 0;

function clickmenu(sCat, sAction){
	var bDone;
	bDone = 0;
	console.log(sCat + " / " + sAction);
	switch (sAction){
		case "logout":
			event.preventDefault();
		    document.getElementById("logout-form").submit();
		    bDone = 1;	
		break;
	}
	if (!bDone){
		window.location.href = "/" + sCat + "/" + sAction;
	}
}

function menu(sID) {
	var eDD, aL, aM, iI;
	eDD = document.getElementById(sID);
	iActiveMenu = parseInt(sID.split("_")[1]);
	aM = document.getElementsByClassName("w3show");
	if (aM[0]){
		iI =0;
		while (aM[iI]){
			aL = aM[iI].classList;
			aL.toggle("w3show");
			iI++;
		}
	}
	aL = eDD.classList;
	aL.toggle("w3show");
}

window.onclick = function(oEvent) {
	var eTarget, eNav;
	eTarget = oEvent.target
  if (!eTarget.matches('.w3dbtn')) {
  var eNav = document.getElementById("nav_" + iActiveMenu);
    if (eNav.classList.contains('w3show')) {
      eNav.classList.remove('w3show');
    }
  }
}
</script>

<?php $aM = Config::get('app.menuadmin'); 
$iI = 1;?>
@foreach ($aM as $aMenu)
<div class="navmain">
	<div class="w3navbar">
		<div class="w3ddown">
			<button @if (isset($aMenu[3])) style="background: {{ $aMenu[2] }};" @endif class="w3dbtn" onclick="menu('nav_{{ $iI }}')">{{ $aMenu[0] }}&nbsp;&nbsp;<i class="w3caret"></i></button>
		<div class="w3ddown-content" id="nav_{{ $iI }}">
@foreach ($aMenu[1] as $aMenu1)<?php 
$aDest = array($aMenu1);
$aDest[1] = str_replace(' ', '', strtolower($aMenu1));
$aDest[2] = explode('||', $aDest[1]);
if (!isset($aDest[2][1])){
	$aDest[2][1] = $aDest[2][0];
}
$aDest[3] = explode('||', $aDest[0])[0];
?>
			<a onclick="clickmenu('{{ str_replace(' ', '', strtolower($aMenu[0])) }}', '{{ $aDest[2][1] }}')">{{ $aDest[3] }}</a>
@endforeach
		</div>
	</div>
</div></div>
<?php $iI++; ?>
@endforeach                                   
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
<div class="newline"></div>
        <main>
@yield('content')
        </main>
    </div>
    
</body>
</html>
