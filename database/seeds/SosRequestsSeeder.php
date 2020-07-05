<?php

use Illuminate\Database\Seeder;
use App\SosRequest;

class SosRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sos_requests')->insert([
            [
                'user_id' => 1,
                'sos_id' => 1,
                'needed_by' => '2020-04-30',
                'special_instruction' => null,
                'status' => SosRequest::STATUS_PENDING,
                'chat' => '[]',
            ],
        ]);
    }
}
