@extends('layouts.app')

@section('content')
<!-- Logged in! -->
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
        	@if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-12">
        	<home
        		:is-responder="{{ auth()->user()->status == \App\User::STATUS_RESPONDER ? 'true' : 'false' }}"
        		user-id="{{ auth()->user()->id }}"
        		user-name="{{ auth()->user()->getUserName() }}"
        		:types="{{json_encode(\App\Sos::getTypesArray())}}"
				:current-tab-index="{{ auth()->user()->getHomeTabIndexCache() }}"        		
        	></home>
        </div>
    </div>
</div>
@endsection
