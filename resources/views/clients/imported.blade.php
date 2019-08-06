@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Imported') }} {{ $oData['sSingle'] }}</div>
				@include('alerts')
			</div>



















			<div class="card-body">
				<p>You have imported <strong>{{ $oData['iTotal'] }}</strong> {{ $oData['sSingle'] }}.</p>
				<p><strong>{{ $oData['iDeleted'] }}</strong> {{ $oData['sSingle'] }}  were overwritten. Click to view the {{ $oData['sSingle'] }}.</p>
				<p>You now have <strong>{{ $oData['iNumClients'] }}</strong> {{ $oData['sSingle'] }} in the database.</p>
				<br><br>
				<a href="../{{ $oData['sSingle'] }}"><button>View {{ $oData['sSingle'] }}</button>                   
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
