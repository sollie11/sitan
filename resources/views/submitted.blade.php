@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col100">
		<div class="card100">
			<div class="card-header" style="height: 6em;">
				<div class="card-header-title">{{ __('Questionnaire submitted') }}</div>
				@include('alerts')
			</div>
			<div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
<p>The completed form was sent to the responsible persons. Please feel free to make more adjustments and submit again. The better information we have, the better we can understand your business!</p>
<br><br>
<table style="width: 40%"><tr>
<td><button onclick="window.open('.', '_parent');">Home</button></td>
</tr></table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
