@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                	<div class="mainheadersubmenu" id="mainheader">
                		<div style="float: left;">Upload Error</div>
						<div style="float: right;">{{ session()->get('sBusinessName') }}</div>
                	</div>
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
