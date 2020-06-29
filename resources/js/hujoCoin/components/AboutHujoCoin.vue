<template>
<div class="d-flex justify-content-center">
<b-card
	border-variant="secondary"
	header="About Hujo Coin"
	style="max-width: 70rem;"
>
	<b-card-body title="What is Hujo?">
   		<b-card-text>
   			Hujo (扶助) is “assistance” in Japanese.
   		</b-card-text>
	</b-card-body>
	<b-card-body title="What is Hujo Coin?">
		<b-card-text>Hujo Coin is a community token that helps facilitate local community 
		interaction, making it easier for neighbours to help one another. </b-card-text>
	</b-card-body>
	
	<b-card-body title="Explore Subjects:">
		<b-card-text>
			<!-- CONCEPT -->
			<b-card no-body border-variant="primary" class="mb-1">
		 		<b-card-header header-tag="header" class="p-1" role="tab">
		        	<b-button block v-b-toggle.accordion-1 variant="outline-primary">Concept</b-button>
		      	</b-card-header>
		      	<b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
			       	<b-card-body>
			       	<b-card-text>
			       		When traveling the world I have noticed something different in some 
			       		community that my own community in a big city is missing, and that is 
			       		their relationship with their neighbours. Coming home I started to notice 
			       		how I mostly interact with people from some kilometres away and not have 
			       		a clue who is living right next door, their names, who they are, and how 
			       		can we help one another, given there are so much we could share because 
			       		of our close proximity.  
		       		</b-card-text>
					<b-card-text>
						During the first wave of Covid-19 I also saw on the news how for example 
						some communities in China got together on their own to take turn in helping 
						one another to buy groceries and other essentials, so to minimize overall 
						exposure for everyone. Elsewhere I see only close friends and relatives 
						helping one another, leaving others in the same community needing to manage 
						on their own.
					</b-card-text> 
					</b-card-body>
		      	</b-collapse>
		    </b-card>
		    
		    <!-- FEATURES -->
		    <b-card no-body border-variant="primary" class="mb-1">
		 		<b-card-header header-tag="header" class="p-1" role="tab">
		        	<b-button block v-b-toggle.accordion-2 variant="outline-primary">Features</b-button>
		      	</b-card-header>
		      	<b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
			       	<b-card-body>
			       	<b-card-text>
			       		The Hujo Coin is implemented with a smart contract that binds user to a 
			       		certain rules so that the token will remain with its intended usage, 
			       		protecting everyone’s interest. These features include:
			       		<ul>
			       			<li class="my-3">
			       				The token is based on your locale. It automatically restricts 
			       				users to exchange within approximately 100km of your location. 
			       				Platforms can further increase this restriction if desired. 
			       				Unlike other community tokens which is bind to a certain city 
			       				or boundaries, this arrangement will always ensure you can 
			       				exchange with people in your area no matter where you live and 
			       				is not bound by a certain top down jurisdiction. 
		       				</li>
		       				<li class="my-3">
		       					There’s an upper limit to the number of coins a person can hold, 
		       					currently set at 20. This will ensure everyone the incentive to 
		       					receive help at some point, creating a more balanced reciprocal 
		       					ecology. This will also discourage people from running business 
		       					with the coin, so to maintain its original intention. This also 
		       					intentionally run contrast to the actual money system we use 
		       					everyday, where certain individuals and companies hoard huge 
		       					amount of money, creating huge disparity and social problems.
		       				</li>
		       				<li class="my-3">
		       					There’s an idle tax, currently set at 1 coin per month. This serves 
		       					to encourage the continuation of exchange, ensuring the community’s 
		       					vitality.
	       					</li>
	       					<li class="my-3"> 
								The platform that uses Hujo Coin will facilitate the exchange, 
								provide the searching mechanism to connect people in need and 
								people offering help. Having the limits above will further creates 
								incentives to exchange beyond the people you already know and circle 
								of friends.
							</li>
			       		</ul>
					</b-card-text> 
					</b-card-body>
		      	</b-collapse>
		    </b-card>
		    
		    
		    <!-- WHY ETHEREUM? -->
		    <b-card no-body border-variant="primary" class="mb-1">
		 		<b-card-header header-tag="header" class="p-1" role="tab">
		        	<b-button block v-b-toggle.accordion-3 variant="outline-primary">Why Ethereum?</b-button>
		      	</b-card-header>
		      	<b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
			       	<b-card-body>
			       	<b-card-text>
			       		<ul>
			       			<li class="my-3">
			       				Ethereum is one of the most wildly used crypto currency that supports 
			       				smart contract. There is a vibrant community of developers supporting 
			       				and improving the protocol and its security. There is also a general 
			       				desire among developers to stabilize the exchange of currency with 
			       				fiat currency and working toward wilder acceptance as a vehicle for 
			       				exchange rather than for investment and speculative purpose.
		       				</li>
		       				<li class="my-3">
		       					Ethereum, as is based on block chain technology, provide a self 
		       					sustaining way so that once the Hujo Coin is in use it takes on a 
		       					life of its own, independent of any central authority. 
		       				</li>
		       				<li class="my-3">
		       					Ethereum being a public protocol also makes it possible for Hujo Coin 
		       					to be adapted and used by different platforms in the future.
	       					</li>
			       		</ul>
					</b-card-text> 
					</b-card-body>
		      	</b-collapse>
		    </b-card>
		    
		    
		    <!-- COST & RISKS -->
		    <b-card no-body border-variant="primary" class="mb-1">
		 		<b-card-header header-tag="header" class="p-1" role="tab">
		        	<b-button block v-b-toggle.accordion-4 variant="outline-primary">Cost and Risks</b-button>
		      	</b-card-header>
		      	<b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
			       	<b-card-body>
			       	<b-card-text>
			       		<ul>
			       			<li class="my-3">
			       				Since Hujo Coin is based on Ethereum it does require the user to first 
			       				buying Ethereum with their fiat currency from an exchange, and then 
			       				purchase Hujo Coin with Ethereum. However as the protocol become more 
			       				and more accepted there are already reputable exchanges in different 
			       				countries that is backed by government regulations. In Canada these 
			       				exchanges should be registered with 
			       				<a href="https://www10.fintrac-canafe.gc.ca/msb-esm" target="_blank">FINTRAC</a>. 
			       				Also individual only need 
			       				to hold a minimal amount of Ethereum in order to participate as we try 
			       				to keep to cost of Hujo Coin to a minimal.  
		       				</li>
		       				<li class="my-3">
		       					User does require some knowledge of crypto currency as the technology 
		       					is still new to many. Although there are many freely available tutorials 
		       					on how to use it there incurs a learning curve for individuals, and the 
		       					necessary precautions to avoid scams. Community will play a role in this 
		       					to assist with adaptation.
		       				</li>
		       				<li class="my-3">
		       					Using Ethereum or most other block chain currency does incur cost on 
		       					computing power and network usage. This is reflected in the transaction 
		       					fee. This fee fluctuates base on the Ethereum exchange rate and the cost 
		       					of transaction (gas price). Currently this translates to around 10 cents 
		       					a transaction.  
	       					</li>
	       					<li class="my-3">
		       					There is a system fee to use Hujo Coin, currently set a 5 coins a year, 
		       					to compensate for the initiative and development of the coin and the ongoing 
		       					maintenance and development of the Hujo network, community development and 
		       					promotion.
	       					</li>
			       		</ul>
					</b-card-text> 
					</b-card-body>
		      	</b-collapse>
		    </b-card>
		    
		    
		    <!-- FUTURE OF HUJO COIN -->
		    <b-card no-body border-variant="primary" class="mb-1">
		 		<b-card-header header-tag="header" class="p-1" role="tab">
		        	<b-button block v-b-toggle.accordion-5 variant="outline-primary">Future of Hujo Coin</b-button>
		      	</b-card-header>
		      	<b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
			       	<b-card-body>
			       	<b-card-text>
			       		<ul>
			       			<li class="my-3">
			       				Since Hujo Coin is based on a user’s locale rather than a particular 
			       				geographical community, it can potentially be adapted anywhere in the world. 
		       				</li>
		       				<li class="my-3">
		       					Since it is based on a public protocol Ethereum, this makes it possible 
		       					for Hujo Coin to be adapted by other platforms, and the ability to 
		       					exchange among non-affiliated platforms, making it even more decentralized.  
		       				</li>
		       				<li class="my-3">
		       					Plugins will be made public and open source for easy adaptation  
	       					</li>
			       		</ul>
					</b-card-text> 
					</b-card-body>
		      	</b-collapse>
		    </b-card>
		    
	  	</b-card-text>
	  	
	  	<b-card-text>
	  		<div class="text-center">
	  			<b-button variant="primary" href="/hujoCoin">Join Now!</b-button>
  			</div>
	  	</b-card-text>
  	</b-card-body>
  
</b-card>
</div>
</template>
<script>
export default {
    components: {
    },
    props: {
    },
    data() {
        return {
        }
    },
    methods: { 
    },
    computed: {
    },
    mounted() {
    }
}
</script>
<style></style>