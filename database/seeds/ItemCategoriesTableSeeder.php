<?php

use Illuminate\Database\Seeder;

class ItemCategoriesTableSeeder extends Seeder
{
    private const CATEGORIES = [
        1 => 'Baby Products',
        2 => 'Baked Goods',
        3 => 'Beverages',
        4 => 'Canned Goods',
        5 => 'Cleaning Supplies',
        6 => 'Condiments / Sauce',
        7 => 'Deli',
        8 => 'Diary Products',
        9 => 'Fresh Fruits',
        10 => 'Frozen Food',
        11 => 'Household Items',
        12 => 'Instant Food',
        13 => 'Fresh Meat',
        14 => 'Pet Supplies',
        15 => 'Personal Items',
        16 => 'Pharmacy Products',
        17 => 'Seafood',
        18 => 'Snacks',
        19 => 'Spices & Herbs',
        20 => 'Fresh Vegetables',
        21 => 'Others',
    ];
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('item_categories')->insert(
            array_map(function($item, $key){
                return [
                    'id' => $key,
                    'name' => $item,
                ];        
            }, self::CATEGORIES, array_keys($cats))
        );
    }
}



