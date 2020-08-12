@extends('layouts.app')

@section('mainjs')
<script src="{{ asset('js/hujoCoin.js') }}" defer></script>
@endsection

@section('content')
<hujo-coin
	:user="{{Auth()->user()}}"
	:hujo-coin="{{Auth()->user()->hujoCoin()->withTrashed()->first()->toJson()}}"
	:long-lat="{{json_encode(Auth()->user()->getLongLat())}}"
></hujo-coin>
@endsection
