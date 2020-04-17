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
                'description' => 'Superstore Hastings', 
                'delivery_option' => Sos::DELIVARY_OPTION_DOORFRONT,
                //'delivery_instruction' => NULL,
                'payment_option' => Sos::PAYMENT_OPTION_CASH, 
                //'payment_other_description' => NULL, 
                'other_instruction' => NULL,
                'vendor_name' => 'Superstore',
                'vendor_address' => '123 Hastings street',
                //'chat' => '[{"date": "2020-04-01T19:20:16.999Z", "user": 1, "isNew": false, "message": "ABC"}, {"date": "2020-04-01T19:20:16.999Z", "user": 2, "isNew": false, "message": "EFG"}, {"date": "2020-04-02T04:46:12.653Z", "user": "David Superman", "isNew": true, "message": "Hey how you doing? "}, {"date": "2020-04-02T04:47:17.061Z", "user": "David Superman", "isNew": true, "message": "I got a quote: \"I am no superman\", and a single: \'ok\'"}]', 
                //'receipt_image' => NULL,
                //'responded_by' => 2,
                //'status' => Ask::STATUS_PENDING,
                //'needed_by' => '2020-04-03',
                'created_by' => 1,
            ],
            [
                'name' => 'Drugstore run',
                'description' => 'get cold medicince',
                'delivery_option' => Sos::DELIVARY_OPTION_DOORFRONT,
                //'delivery_instruction' => NULL,
                'payment_option' => Sos::PAYMENT_OPTION_CASH,
                //'payment_other_description' => NULL,
                'other_instruction' => NULL,
                'vendor_name' => 'Supersave',
                'vendor_address' => '333 Hastings street',
                //'chat' => NULL,
                //'receipt_image' => NULL,
                //'responded_by' => NULL,
                //'status' => Ask::STATUS_PENDING,
                //'needed_by' => '2020-04-03',
                'created_by' => 1,
            ],
            [
                'name' => 'Weed run',
                'description' => 'Get from guy in corner',
                'delivery_option' => Sos::DELIVARY_OPTION_DOORFRONT,
                //'delivery_instruction' => NULL,
                'payment_option' => Sos::PAYMENT_OPTION_CASH,
                //'payment_other_description' => NULL,
                'other_instruction' => NULL,
                'vendor_name' => 'Guy in corner',
                'vendor_address' => 'DTES',
                //'chat' => NULL,
                //'receipt_image' => NULL,
                //'responded_by' => NULL,
                //'status' => Ask::STATUS_PENDING,
                //'needed_by' => '2020-04-04',
                'created_by' => 1,
            ],
        ]);
    }
}
