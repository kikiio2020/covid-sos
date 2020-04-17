<?php

use Illuminate\Database\Seeder;
use App\Ask;

class AsksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('asks')->insert([
            [
                'user_id' => 1,
                'sos_id' => 1,
                'needed_by' => '2020-04-30',
                'special_instruction' => null,
                'status' => Ask::STATUS_PENDING,
            ],
        ]);
    }
}
