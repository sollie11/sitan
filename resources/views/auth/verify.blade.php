@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
				<div class="card-header-title">{{ __('Verify your email address') }}</div>
				@include('alerts')
			</div>
			
			<div class="card-body">
				<span>{{ __('Before proceeding, please check your email for a verification link.') }}</span>
				<span>{{ __('If you did not receive the email') }}, <a class="colornodecor" href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</span>
			</div>
		</div>
	</div>
</div>
@endsection
