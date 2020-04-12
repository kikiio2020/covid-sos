@extends('layouts.app')

@section('content')
<profile
	:user-profile="{{Auth()->user()->toJson()}}"
	:status-captions="{{json_encode(App\User::getStatusCaptions())}}"
></profile>
@endsection
