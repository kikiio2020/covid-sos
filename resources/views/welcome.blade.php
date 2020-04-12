@extends('layouts.app')

@section('content')    
<b-container>
	<b-row>
	<b-col>
    	<b-card no-body class="mb-1">
        	<b-card-header header-tag="header" class="p-1" role="tab">
            	<b-button block href="#" v-b-toggle.accordion-1 variant="info" class="font-weight-bold">
            		Welcome message
        		</b-button>
          	</b-card-header>
          	<b-collapse id="accordion-1" accordion="intro">
            	<b-card-body>
              		<b-card-text>
              			<p>Hi,</p>
              			<p>
              				I created this site, hoping it will never need to be used widely. Nevertheless, here are the objectives and intentions of the site. I welcome your feedback.   
              			</p>
              			<p>
              				<ol>
              					<li>The site is intended for those who are currently in self-isolation  due to the virus 
              					(confirmed or not), to anonymously send out request  for help to get daily essentials. 
              					Due to shortage of tests in many countries, it might be necessary to self-diagnose. 
              					By making it easier for people to self-quarantine, hopefully more people will self-isolate 
              					even with very mild or suspected symptoms before infecting other more vulnerable populations.
              					</li> 
        						<li>The site will try to match those neighbours living nearby who may be able to help get 
        						the essentials and delivery to the person in need.
        						</li> 
        						<li>The system will gather the geo data and gives an idea where the virus 
        						maybe spreading. Making this information available to us all will help as 
        						A) government currently cannot test everyone, and would not publish this 
        						information,  B) rather than wait to the point of complete lock down, we 
        						ought to do our part to self-isolate and organize only a few people out to 
        						get essentials.
        						</li>
        					</ol>
        				</p>
        				<p>This site is not intended to:</p>
        				<p>
        					<ol>
        						<li>The site is not intended to spread fear. The virus sees no border or 
        						skin colour. Fear will not help, but information will. Anyone may be 
        						infected. Most people will get only mild symptoms, but the same virus 
        						might kill the person next to you. The vaccine will not be available in 
        						another year. Right now the only way is that we all play our part to take 
        						down the virus.</li>
        						<li>The site is not intended to make money for anyone. The data collected 
        						will not be sold. Helpers should not expect to be compensated. I myself 
        						volunteered my time to create the site. As it stands now, we all are 
        						susceptible and may be infected at some point. We will all need each other’s 
        						help. The sooner we get a hold of the situation, the sooner our lives and 
        						economy will get back to “normal”.  We are all in this together! This may be 
        						the greatest test to humanity for a long time!   						  
              					</li>
              				</ol>
              			</p>
              			<p>Good luck to us all!</p>
              		</b-card-text>
                </b-card-body>
          	</b-collapse>
    	</b-card>
	</b-col>
	</b-row>
	@if (session('status'))
        <b-row>
    		<b-col class="alert alert-success" >{{ session('status') }}</b-col>
    	</b-row>
    @endif
	<b-row class="my-5">
		<b-col sm=2></b-col>
		<b-col>
			<b-card no-body class="mb-1">
            	<b-card-header header-tag="header" class="p-1" role="tab">
                	<b-button block href="#" v-b-toggle.menu-1 variant="Secondary" class="font-weight-bold">
                		Login
            		</b-button>
              	</b-card-header>
              	<b-collapse id="menu-1" visible accordion="menu">
                	<b-card-body>
                  		

					<form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>




                    </b-card-body>
              	</b-collapse>
    		</b-card>
    		
			<b-card no-body class="mb-1">
    			<b-card-header header-tag="header" class="p-1" role="tab">
                	<b-button block href="#" v-b-toggle.menu-2 variant="Secondary" class="font-weight-bold">
                		Register
            		</b-button>
              	</b-card-header>
              	<b-collapse id="menu-2" accordion="menu">
                	<b-card-body>
                  		
                  		
                  		
                  		
              		<form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name (Optional)') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

						<div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Address') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="address" value="{{ old('name') }}" required autocomplete="address" autofocus>

                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                  		
                  		
                  		
                  		
                  		
                    </b-card-body>
              	</b-collapse>
    		</b-card>
    		
    		<b-card no-body class="mb-1">
    			<b-card-header header-tag="header" class="p-1" role="tab">
                	<b-button block href="#" v-b-toggle.menu-3 variant="Secondary" class="font-weight-bold">
                		Resources
            		</b-button>
              	</b-card-header>
              	<b-collapse id="menu-3" accordion="menu">
                	<b-card-body>
                  		FAQs
                    </b-card-body>
              	</b-collapse>
              	<b-collapse id="menu-3" accordion="menu">
                	<b-card-body>
                  		Related Articles
                    </b-card-body>
              	</b-collapse>
    		</b-card>
    		
    		<b-card no-body class="mb-1">
    			<b-card-header header-tag="header" class="p-1" role="tab">
                	<b-button block href="#" v-b-toggle.menu-4 variant="Secondary" class="font-weight-bold">
                		Connect
            		</b-button>
              	</b-card-header>
              	<b-collapse id="menu-4" accordion="menu">
                	<b-card-body>
                  		socials
                    </b-card-body>
              	</b-collapse>
    		</b-card>
    		
		</b-col>
		<b-col sm=2></b-col>
	</b-row>
</b-container>
@endsection
