<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    
    private $validator;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $this->validator = Validator::make($data, [
            'name' => ['string', 'max:255', 'nullable'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'address' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        
        //session()->flash('general_registration_error', 'Address server fail');
        //throw new ValidationException($validator);
        
        /*$validator->after(function($validator){
            $validator->errors()->add('general', 'test message');
        });
        */
        
        
        return $this->validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $geoLookupUrl = "https://geocoder.ca/?locate={$data['address']}&geoit=XML&json=1";
        try {
            $response = Http::get($geoLookupUrl);
        } catch (\Exception $e) {
            $this->validator->errors()->add('general', 'Location lookup fails, please try again later.');
            throw new ValidationException($this->validator);
        }
        $this->validator->after(function($validator) use ($response) {
            if (!$response->successful() || !$response->json()) {
                $validator->errors()->add('general', 'Location lookup fails, please try again later.');
            }
            $data = $response->json();
            if (
                isset($data['error'])
                || !isset($data['longt']) || !$data['longt'] 
                || !isset($data['latt']) || !$data['latt'] 
            ) {
                $validator->errors()->add('address', 'Invalid address or format. Please make sure address is correct.');
            }
        });
        if ($this->validator->fails()) {
            throw new ValidationException($this->validator);
        }
                
        $geoData = $response->json();
        
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'],
            'longlat' => \DB::raw("POINT({$geoData['longt']}, {$geoData['latt']})"),
            'postal' => $geoData['postal'],
        ]);
    }
}
