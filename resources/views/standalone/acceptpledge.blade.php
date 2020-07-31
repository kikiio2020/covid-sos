{{-- Standalone model view --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
			<accept-pledge
				request-id="{{ $sosRequest->id }}"
				request-name="{{ $sosRequest->sos->name }}"
				responder-name="{{ $sosRequest->responder->name }}"
				neededby-date="{{ Carbon\Carbon::parse($sosRequest->needed_by)->format('M d, Y') }}"
			></accept-pledge>
        </div>
    </div>
</div>
@endsection

