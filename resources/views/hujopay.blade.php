@extends('layouts.app')

@section('mainjs')
<script src="{{ asset('js/hujoCoin.js') }}" defer></script>
@endsection

@section('content')
<hujo-pay
	:request-id="{{ $requestId }}"
	:user="{{Auth()->user()}}"
	:hujo-coin="{{Auth()->user()->hujoCoin->toJson()}}"
></hujo-pay>
@endsection
