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
        		:delivery-options="{{json_encode(\App\Sos::getDeliveryOptionsArray())}}"
                :payment-options="{{json_encode(\App\Sos::getPaymentOptionsArray())}}"
        		user-id="{{ auth()->user()->id }}"
        		user-name="{{ auth()->user()->getUserName() }}"
        		:sos-options="{{ json_encode($sosOptions) }}"
        	></home>
        
    		
            <!-- <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
            
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection
