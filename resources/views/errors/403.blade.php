@extends('layouts.app')

@section('content')
<div style="min-height:40%;min-height:40vh;" class="d-flex flex-column justify-content-center align-items-center align-middle">
    
    <div class="m-5">
		@if (empty($exception->getMessage())) 
			Oops! An Error Occurred <br>
			The server returned a "403 Forbidden".
		@else
			{{ $exception->getMessage() }}
		@endif
    </div>
    <div class="">
    	<b-button onClick="window.location.href='/'">Home</b-button>
    </div>
</div>
@endsection
