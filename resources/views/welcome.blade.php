<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>{{ getenv('APP_NAME') }}</title>
		<style>
html{
	background: #ffffff;
	color: #666666;
	font-family: Sans;
}
.bodyfull{
	width: 100%;
	height: 100%;
	text-align: center;
}
.centerslogan{
	font-size: 1.2em;
	font-weight: normal;
	width: 100%;
	margin: 0 auto 0 auto;
}
.centertitle{
	font-size: 4em;
	font-weight: lighter;
	width: 100%;
	margin: 0 auto 0 auto;
}
.toprightlinks{
	text-decoration: none;
	padding-left: 4em;
	float: right;
	text-transform: uppercase;
	color: #666666;
}
.toprightmenu{
	font-size: 0.8em;
	font-weight: bold;
	float: right;
	width: 100%;
	margin: 0 2em 2em 0;
}
li{
	float: right;
}
ul{
	list-style: none;
}
        </style>
	</head>
	<body>
		<div class="bodyfull">
@if (Route::has('login'))
		<div class="toprightmenu">
@auth		<a class="toprightlinks" href="{{ url('/home') }}">Home</a>
@else
<ul>
@if (Route::has('register'))
			<li><a class="toprightlinks" href="{{ route('register') }}">Register</a></li>
@endif
			<li><a class="toprightlinks" href="{{ route('login') }}">Login</a></li>
</ul>			
@endauth
		</div>
@endif	<div class="content">
			<img src="logo300x294.png">
			<div class="centertitle">{{ getenv('APP_NAME') }}</div>
			<div class="centerslogan">{{ getenv('APP_SLOGAN') }}</div>
		</div>
	</body>
</html>
