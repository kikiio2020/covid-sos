@extends('layouts.app')

@section('content')
<shoplist
	items="{{App\Item::all()}}"
	:user-profile="{{Auth()->user()->toJson()}}"
	:status-captions="{{json_encode(App\User::getStatusCaptions())}}"
></shoplist>
@endsection
