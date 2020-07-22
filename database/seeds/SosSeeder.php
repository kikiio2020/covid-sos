<?php

use Illuminate\Database\Seeder;
use App\Sos;

class SosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('sos')->insert([
            [
                'name' => 'Grocery run',
                'type' => Sos::TYPE_GROCERY,
                'description' => 'Superstore Hastings', 
                'detail_instructions' => NULL,
                'created_by' => 1,
            ],
            [
                'name' => 'Drugstore run',
                'type' => Sos::TYPE_CHORE,
                'description' => 'get cold medicince',
                'detail_instructions' => NULL,
                'created_by' => 1,
            ],
            [
                'name' => 'Weed run',
                'type' => Sos::TYPE_CHORE,
                'description' => 'Get from guy in corner',
                'detail_instructions' => NULL,
                'created_by' => 1,
            ],
        ]);
    }
}
