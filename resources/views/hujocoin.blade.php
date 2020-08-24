@extends('layouts.app')

@section('mainjs')
<script src="{{ asset('js/hujoCoin.js') }}" defer></script>
@endsection

@section('content')
<hujo-coin
	:user="{{Auth()->user()}}"
	@if (Auth()->user()->hujoCoin()->withTrashed()->first())
		:hujo-coin="{{Auth()->user()->hujoCoin()->withTrashed()->first()->toJson()}}"
	@else
		:hujo-coin="{{new App\HujoCoin()}}"
	@endif
	:long-lat="{{json_encode(Auth()->user()->getLongLat())}}"
></hujo-coin>
@endsection
