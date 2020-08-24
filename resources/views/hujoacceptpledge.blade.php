@extends('layouts.app')

@section('mainjs')
<script src="{{ asset('js/hujoCoin.js') }}" defer></script>
@endsection

@section('content')
<hujo-accept-pledge
	:request-id="{{ $sosRequest->id }}"
	request-name="{{ $sosRequest->sos->name }}"
	responder-name="{{ $sosRequest->responder->name }}"
	neededby-date="{{ Carbon\Carbon::parse($sosRequest->needed_by)->format('M d, Y') }}"
	:hujo-coin="{{Auth()->user()->hujoCoin->toJson()}}"
	:hujo-coin-recipient="{{$sosRequest->responder->hujoCoin->toJson()}}"
></hujo-accept-pledge>
@endsection
