{{-- Standalone model view --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
			<b-card 
				class="card-align-center"
				title="{{ $sosRequest->sos->name }}"
			>
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Needed By:</div>
					<div class="ml-5">{{ $sosRequest->needed_by }}</div>
				</div>
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Description:</div>
					<div class="ml-5">{{ $sosRequest->sos->description }}</div>
				</div>
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Signed off by Requester on:</div>
					<div class="ml-5">{{ $sosRequest->user_approved }}</div>
				</div>
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Signed off by Responder on:</div>
					<div class="ml-5">{{ $sosRequest->responder_approved }}</div>
				</div>
				
				@if($sosRequest->sos->shoplistItem()->count())				
				<div class="d-flex flex-column justify-content-left mt-3">
						<b-button v-b-toggle.shoplist variant="primary">Shop list</b-button>					
						<b-collapse id="shoplist" class="mt-2">
                            <!-- <shoplist-items-read-field
                            	placeholder=""
                            	style="max-height: 300px; margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch;"
                            	value="@json($sosRequest->sos->shoplistItem())"
                            ></shoplist-items-read-field>
                             -->
                            <b-list-group
                            	style="max-height: 300px; margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch;"
                            >
                                @foreach ($sosRequest->sos->shoplistItem()->get() as $shoplistItem) 
                                <b-list-group-item class="flex-column align-items-start">
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            {{ $shoplistItem->item->name }}
                                            <small> (< ${{ $shoplistItem->max_dollar }})</small>
                                        </div>
                                        <div>
                                        	<b-badge variant="primary">{{ $shoplistItem->quantity }}</b-badge>
                                    	</div>
                                    </div>
                                    @isset($shoplistItem->description)
                                        <p><small> 
                                        	{{ $shoplistItem->description }}
                                        </small></p>
                                    @endisset
                                    
                                </b-list-group-item>
								@endforeach
							</b-list-group>
                          </b-collapse>
				</div>
				@endif
				
				<!-- 
				<b-card 
    				class="mt-3 mb-3"
    				title-tag="h6"
    				class="card-align-center"
    				title="{{ $sosRequest->sos->vendor_name }}: {{ $sosRequest->sos->vendor_address }}"
    			>	
    				<div class="d-flex flex-column justify-content-left">
						<b-button v-b-toggle.map variant="primary">Map</b-button>
						<b-collapse id="map" class="mt-2">
    						<b-embed
                                type="iframe"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAw9Bweau13MobNbb8ZUCG35fId_KE2pEI&q={{ Str::slug($sosRequest->sos->vendor_address) }}"
                                allowfullscreen
                                aspect="21by9"
                            ></b-embed>                            
                      	</b-collapse>
    				</div>
				</b-card>
				 -->
				 
				<div class="d-flex flex-column mt-5 mb-5 justify-content-center font-weight-bold">
					Communication Log:
					<chat-log chat="{{ $sosRequest->chat }}"></chat-log>
				</div>
				
				<div class="d-flex justify-content-end">
					<div class="">
						@if( $isInProgress )
							<button-complete sos-request-id="{{ $sosRequest->id }}"></button-complete>
						@endif
						<b-button @click="this.window.location = '/'">Close</b-button>
					</div>
				</div>
				
			</b-card>        	
        </div>
    </div>
</div>
@endsection

