<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => 'Sally Inneed',
                'email' => '123@123.com',
                'password' => Hash::make('123123123123'),
                'address' => '8888 University Dr, Burnaby',
                'longlat' => \DB::raw('POINT(-122.921740, 49.279880)'),
                'postal' => 'V5A1S6',
                'status' => User::STATUS_QUARANTINE,
                'status_set_at' => '2020-04-03 00:00:00',
                'quarantined_at' => NULL,
                'flag' => 0,
                'email_verified_at' => '2020-03-30 20:52:26',
                //'api_token' => Str::random(60),
            ],
            [
                'name' => 'David Superman',
                'email' => 'daniel@kikiio.com',
                'password' => Hash::make('123123123123'),
                'address' => '9000 University High St, Burnaby',
                'longlat' => \DB::raw('POINT(-122.910020, 49.277640)'),
                'postal' => 'V5A4Y8',
                'status' => User::STATUS_RESPONDER,
                'status_set_at' => '2020-04-03 00:00:00',
                'quarantined_at' => NULL,
                'flag' => 0,
                'email_verified_at' => '2020-03-30 20:52:26',
                //'api_token' => Str::random(60),
            ],
            [
                'name' => 'Tee Homy',
                'email' => '333@333.com',
                'password' => Hash::make('123123123123'),
                'address' => '2301 Dorman Drive Burnaby',
                'longlat' => \DB::raw('POINT(-122.57083, 49.15462)'),
                'postal' => 'V5A2V3',
                'status' => User::STATUS_RESPONDER,
                'status_set_at' => '2020-04-03 00:00:00',
                'quarantined_at' => NULL,
                'flag' => 0,
                'email_verified_at' => '2020-03-30 20:52:26',
                //'api_token' => Str::random(60),
            ],
        ]);
    }
}
