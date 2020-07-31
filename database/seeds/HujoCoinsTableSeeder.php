<?php

use Illuminate\Database\Seeder;

class HujoCoinsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('hujo_coins')->insert([
            [
                'user_id' => 1,
                'crypto_address' => 'dummy record 1',
            ],
            [
                'user_id' => 2,
                'crypto_address' => 'dummy record 2',
            ],
        ]);
    }
}
