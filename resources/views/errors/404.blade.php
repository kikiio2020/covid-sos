@extends('layouts.app')

@section('content')
<div style="min-height:40%;min-height:40vh;" class="d-flex flex-column justify-content-center align-items-center align-middle">
    
    <div class="m-5">
		@if (empty($exception->getMessage()) || false === config()['app']['debug'])
		Oops! The information you are looking for is not found.
		@else
		{{ $exception->getMessage() }}
		@endempty
    </div>
    <div class="">
    	<b-button onClick="window.location.href='/'">Home</b-button>
    </div>
</div>
@endsection
