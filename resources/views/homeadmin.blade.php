@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Admin Home') }}</div>
				@include('alerts')
			</div>



















			<div class="card-body">
				<span>You are an administrator on this site. Please respect the rules and goals of our company.</span>
				<br><span>We have a total of <strong>{{ $oData['iTotalUsers'] }}</strong> users.</span>
				<br><span>There are <strong>{{ $oData['iTotalAdmins'] }}</strong> administrators and <strong>{{ $oData['iTotalClients'] }}</strong> users in our database.</span>
				<br><span><strong>{{ $oData['iTotalDeleted'] }}</strong> users should be permanently deleted.</span>
			</div>
		</div>
	</div>
</div>
@endsection
