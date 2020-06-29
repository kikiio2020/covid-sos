@extends('layouts.app')

@section('mainjs')
<script src="{{ asset('js/hujoCoin.js') }}" defer></script>
@endsection

@section('content')
<hujo-coin
	:user="{{Auth()->user()}}"
	:hujo-coin="{{Auth()->user()->hujoCoin->toJson()}}"
	:long-lat="{{json_encode(Auth()->user()->getLongLat())}}"
></hujo-coin>
@endsection
