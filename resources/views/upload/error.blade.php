@extends('layouts.app')
@section('content')
<div class="container">
	<div class="col50">
		<div class="card">
			<div class="card-header">
                	<div class="card-header-title">{{ __('Upload error') }}</div>
                	@include('alerts')
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
@foreach ($aErrors as $sError)
<p>{{ $sError }}</p>
@endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
