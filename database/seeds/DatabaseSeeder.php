<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        if (config('app.env') == 'local') {
            $this->call(UsersTableSeeder::class);
            $this->call(SosSeeder::class);
            //$this->call(SosRequestsSeeder::class);
            $this->call(ItemsTableSeeder::class);
            //$this->call(HujoCoinsTableSeeder::class);
        }
    }
}
