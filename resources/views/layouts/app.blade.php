<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'COVID-SOS') }}</title>

	<link rel="shortcut icon" type="image/png" href="/images/favicon.png">

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
    html, body {
      height: 100%;
      font-family: sans-serif;
    }
    body {
      display: flex;
      flex-direction: column;
    }
    .content {
      flex: 1 0 auto;
    }
    .body-content {
        height: 100%
    }
    .footer {
      font-size: small;
      color: #9b9b9b;
      border-top: 1px solid #dddddd;
    }
    .footer a {
        color: #9b9b9b;
    }
    .footer a:hover {
        color: #9b9b9b;
        text-decoration: underline;
    }
    </style>
</head>
<body>
    <div id="app" class="content">
    <b-navbar toggleable="lg" style='background-color:#9daab5' type="light">
    	<b-navbar-brand href="/">
    		<div style="font-weight:bold; font-size:xx-large; margin: -10px 10px -10px 20px;">
    			cov<span class="text-white">i</span>d-<span class="text-white">S</span><span class="text-danger">O</span><span class="text-white">S</span>
			</div>
		</b-navbar-brand>

		<b-navbar-toggle target="nav-collapse"></b-navbar-toggle>
		<b-collapse id="nav-collapse" is-nav>
    		
    
    		<b-navbar-nav>
                @auth
                    <b-nav-item href="#">SOS</b-nav-item>
                    <b-nav-item href="#">Help Someone</b-nav-item>
                @else
					<b-nav-item href="{{ route('register') }}">{{ __('Register') }}</b-nav-item>
                @endauth
                <b-nav-item-dropdown text="Resources">
                	<b-nav-item href="#"><span class="text-black-50 mx-3">FAQs</span></b-nav-item>
                	<b-nav-item href="#"><span class="text-black-50 mx-3">Learn More</span></b-nav-item>
            	</b-nav-item-dropdown>
                <b-nav-item href="#">Get In Touch</b-nav-item>
          	</b-navbar-nav>
    
    		@auth
                <!-- Right aligned nav items -->
                <b-navbar-nav class="ml-auto">
                    <b-nav-item-dropdown right >
                        <template v-slot:button-content>
                            <strong class="text-white">{{ Auth::user()->email }}</strong>
                        </template>
                      	<b-dropdown-item href="/profile"><span class="text-black-50">Profile</span></b-dropdown-item>
                      	<b-dropdown-item 
                      		href="{{ route('logout') }}"
                      		onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                  		>
                  			<span class="text-black-50">{{ __('Logout') }}</span>
                      	</b-dropdown-item>
                    </b-nav-item-dropdown>
                </b-navbar-nav>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
            @endauth
        </b-collapse>
	</b-navbar>
        
        
        <!-- 
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar --//>
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar --//>
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links --//>
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        -->

        <main class="py-4">
            @if (cache('show_welcome') && 'about' !== request()->path())
        		<b-container><b-row><b-col>
        			<welcome-alert></welcome-alert>
    			</b-col></b-row></b-container>
        	@endif
            @yield('content')
        </main>
    </div>
    
    <footer class="footer mt-3 pt-3" style="background-color:#F7F7F7">
    	<div class="container">
    		<div class="row">
    			<div class="col-4 text-center">
    				<div class="container">
        				<div class="row"><div class="col-4 text-center">
                			<ul>
                				<li><a href="">ABOUT</a></li>
                				<li><a href="">CREDITS</a></li>
                				<li><a href="">GET INVLOVED</a></li>
                				<li><a href="">RESCOURCES</a></li>
                				<li><a href="">PRIVACY POLICY</a></li>
            				</ul>
                		</div></div>
    				</div>
    			</div>
    			<div class="col-4 text-center">
    				<div class="container">
        				<div class="row"><div class="col-4 text-center">
        					
        				</div></div>
    				</div>
				</div>
				<div class="col-4 text-center">
    				<div class="container m-auto">
        				<div class="row"><div class="col-4 text-center">
        					<div style="font-weight:bold; font-size:xx-large; white-space: nowrap;">
                    			covid-S<span class="text-danger">O</span>S
                			</div>
        				</div></div>
        				<div class="row"><div class="col-4 text-center">
        					We're in this together!
        				</div></div>
    				</div>
				</div>
			</div>
    		<div class="row mb-3"></div>
    		<div class="row" style="border-top: 1px solid #9DAAB5">
        		<div class="col-6 text-left">&#169;</div>
        		<div class="col-6 text-right">created by <a href="https://www.kikiio.com">Daniel 'Kikiio' Leung</a> {{ now()->year }}.</div>
    		</div>
    	</div>
    </footer>
</body>
</html>
