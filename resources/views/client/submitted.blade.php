@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header"> 
				<div class="card-header-title">Questionnaire submit</div>
				@include('alerts')
			</div>
			<div class="card-body">
<p>The completed form was sent to the responsible persons. Please feel free to make more adjustments and submit again. The better information we have, the better we can understand your business!</p>
<a href="/home"><button>Home</button></a>
</div>
</div>
</div>
</div>
@endsection
