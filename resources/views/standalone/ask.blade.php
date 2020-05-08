{{-- Standalone model view --}}
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
			<b-card 
				class="card-align-center"
				title="{{ $ask->sos->name }}"
			>
				<b-card-text>{{ $ask->sos->description }}</b-card-text>
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Needed By:</div>
					<div class="ml-5">{{ $ask->needed_by }}</div>
				</div>
				
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Delivery Option:</div>
					<div class="ml-5">{{ __('model.sos.delivery_option.' . $ask->sos->delivery_option) }}</div>
				</div>
				
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Payment Option:</div>
					<div class="ml-5">{{ __('model.sos.payment_option.' . $ask->sos->payment_option) }}</div>
				</div>
				
				@isset($ask->sos->complensation)
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Compensation:</div>
					<div class="ml-5">${{ $ask->sos->complensation }}</div>
				</div>
				@endisset
				
				<div class="d-flex flex-column justify-content-left mt-3">
						<b-button v-b-toggle.shoplist variant="primary">Shop list</b-button>					
						<b-collapse id="shoplist" class="mt-2">
                            <!-- <shoplist-items-read-field
                            	placeholder=""
                            	style="max-height: 300px; margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch;"
                            	value="@json($ask->sos->shoplistItem())"
                            ></shoplist-items-read-field>
                             -->
                            
                            <b-list-group
                            	style="max-height: 300px; margin-bottom: 10px; overflow:scroll; -webkit-overflow-scrolling: touch;"
                            >
                                @foreach ($ask->sos->shoplistItem()->get() as $shoplistItem) 
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
				
				@isset($ask->special_instruction)
				<div class="d-flex justify-content-left">
					<div class="font-weight-bold">Special Instruction:</div>
					<div class="ml-5">{{ $ask->special_instruction }}</div>
				</div>
				@endisset
				
				<b-card 
    				class="mt-3 mb-3"
    				title-tag="h6"
    				class="card-align-center"
    				title="{{ $ask->sos->vendor_name }}: {{ $ask->sos->vendor_address }}"
    			>	
    				<div class="d-flex flex-column justify-content-left">
						<b-button v-b-toggle.map variant="primary">Map</b-button>
						<b-collapse id="map" class="mt-2">
    						<b-embed
                                type="iframe"
                                src="https://www.google.com/maps/embed/v1/place?key=AIzaSyAw9Bweau13MobNbb8ZUCG35fId_KE2pEI&q={{ Str::slug($ask->sos->vendor_address) }}"
                                allowfullscreen
                                aspect="21by9"
                            ></b-embed>                            
                      	</b-collapse>
    				</div>
				</b-card>
				
				<div class="d-flex flex-column mt-5 justify-content-center">
					<div class="">
    					<model-chat-field
    						id="chat"
							name="Chat"
							user-name="{{auth()->user()->getUserName()}}"
							caption="Communication"
							placeholder="Communication"
    						api="/webapi/ask"
    						value="{{ $ask->chat }}"
    						:model-id="1"
    						readonly="{{\App\Ask::STATUS_COMPLETED === $ask->status}}"
    					></model-chat-field>
					</div>
				</div>
				
				<div class="d-flex justify-content-end">
					<div class="">
						<b-button @click="this.window.location = '/'">Close</b-button>
					</div>
				</div>
				
			</b-card>        	
        </div>
    </div>
</div>
@endsection

