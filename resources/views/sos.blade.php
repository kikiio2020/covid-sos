@extends('layouts.app')

@section('content')
<sos
	items="{{App\Item::all()}}"
	:user-profile="{{Auth()->user()->toJson()}}"
	:status-captions="{{json_encode(App\User::getStatusCaptions())}}"
></sos>
@endsection
