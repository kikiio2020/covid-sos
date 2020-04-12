<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $baby = [
            1 => 'Baby Food',
            2 => 'Baby Diapers',
            3 => 'Baby Formula',
            4 => 'Baby Lotion',
            5 => 'Baby Wash',
            6 => 'Baby Wipes',
        ];
        
        $bakedGoods = [
            100 => 'Bagels',
            101 => 'Croissants',
            102 => 'Burger Buns',
            103 => 'Cake',
            104 => 'Cookies',
            105 => 'Donuts',
            106 => 'English muffins',
            107 => 'Hotdog Buns',
            108 => 'Pastries',
            109 => 'Fresh bread',
            110 => 'Sliced Bread Brown',
            111 => 'Sliced Bread Seeds',
            112 => 'Sliced Bread White',
            113 => 'Pie',
            114 => 'Pita bread',
            115 => 'Tortillas',
        ];
        
        $beverages = [
            200 => 'Chrysanthemum tea',
            201 => 'Coffee',
            202 => 'Concentrated Fruit juice',
            203 => 'Energy drinks',
            204 => 'Hot Chocolate',
            205 => 'Ice Tea',
            206 => 'Instant Coffee',
            207 => 'Ovaltine',
            208 => 'Real Fruit juice',
            209 => 'Soda pop',
            210 => 'Tea',
        ];
        
        $canned = [
            300 => 'Apple Sauce',
            301 => 'Canned Baked Beans',
            302 => 'Chili',
            303 => 'Chunky Soup',
            304 => 'Canned Fruit',
            305 => 'Canned Mushrooms',
            306 => 'Canned Olives',
            307 => 'Canned Soup',
            308 => 'Canned Tuna',
            309 => 'Luncheon Meat',
            310 => 'Tomato Beans',
            311 => 'Canned Veggies',
        ];
        
        $cleaning = [
            400 => 'Air freshener',
            401 => 'Bathroom cleaner',
            402 => 'Bleach',
            403 => 'Dishwashing soap / Detergent',
            404 => 'Dishwasher soap',
            405 => 'Facial Tissue / Kleenex',
            406 => 'Garbage bags',
            407 => 'Glass cleaner / Windex',
            408 => 'Laundry Detergent',
            409 => 'Napkins',
            410 => 'Paper towels',
            411 => 'Sponges / Scrubbers',
            412 => 'Toilet paper',
        ];
        
        $condiments = [
            500 => 'Baking powder / Soda',
            501 => 'BBQ sauce',
            502 => 'Bread Crumbs',
            503 => 'Cooking Alcohol',
            504 => 'Flour',
            505 => 'Gravy',
            506 => 'Honey',
            507 => 'Hot Sauce',
            508 => 'Jam',
            509 => 'Ketchup', 
            510 => 'Mustard',
            511 => 'Mayonnaise',
            512 => 'Oyster sauce',
            513 => 'Pasta sauce',
            514 => 'Peanut Butter',
            515 => 'Red Pepper Paste',
            516 => 'Relish',
            517 => 'Salad dressing',
            518 => 'Salsa',
            519 => 'Sandwich Spread',
            520 => 'Soy sauce',
            521 => 'Steak sauce',
            522 => 'Syrup', 
            523 => 'Worcestershire sauce',
            524 => 'Yeast',
        ];
        
        $deli = [
            600 => 'Bacon',
            601 => 'BBQ Chicken',
            602 => 'BBQ Pork',
            603 => 'Bologna',
            604 => 'Ham',
            605 => 'Regular Sausages',
            606 => 'Salami',
            607 => 'Sausages',
            608 => 'Sliced Beef Deli',
            609 => 'Sliced Chicken Deli',
            610 => 'Smokie Sausages',
        ];
        
        $diary = [
            700 => 'Butter / Margarine',
            701 => 'Chocolate Milk',
            702 => 'Cottage Cheese',
            703 => 'Sandwich Sliced Cheese',
            704 => 'Eggs',
            705 => 'Half & half Milk',
            706 => 'Milk',
            707 => 'Sour cream',
            708 => 'Soy Milk',
            709 => 'Tofu',
            710 => 'Whipped cream',
            711 => 'Yogurt',
        ];
        
        $fruits = [
            800 => 'Apples',
            801 => 'Avocados',
            802 => 'Bananas',
            803 => 'Berries',
            804 => 'Cantaloupe',
            805 => 'Cherries',
            806 => 'Coconuts',
            807 => 'Dragon Fruit',
            808 => 'Durian',
            809 => 'Granadilla',
            810 => 'Grapefruit',
            811 => 'Grapes',
            812 => 'Honeydew',
            813 => 'Kiwis',
            814 => 'Lemons / Limes',
            815 => 'Longan',
            816 => 'Lychee',
            817 => 'Mangos',
            818 => 'Oranges',
            819 => 'Papaya',
            820 => 'Peaches',
            821 => 'Pineapples',
            822 => 'Nectarines',
            823 => 'Pears',
            824 => 'Plums',
            825 => 'Pomegranate',
            826 => 'Strawberries',
            827 => 'Watermelon',
        ];
        
        $frozen = [
            900 => 'Ice Cream',
            901 => 'Ice Cream Bars',
            902 => 'Ice Cream Sandwich',
            903 => 'Drumstick Ice Cream Cones',
            904 => 'Frozen Dinner',
            905 => 'Frozen Pie',
            906 => 'Beef Patty',
            907 => 'Vegerian Patty',
            908 => 'Chickent Nuggets',
            909 => 'Chickent Strips',
            910 => 'Fish & Chips',
        ];
        
        $household = [
            1000 => 'Aluminum foil',
            1001 => 'Batteries',
            1002 => 'Freezer bags',
            1003 => 'Light Bulbs',
            1004 => 'Non-stick spray',
            1005 => 'Plastic wrap',
            1006 => 'Sandwich bags',
            1007 => 'Wax paper',
        ];
        
        $instantFood = [
            1100 => 'Cereal',
            1101 => 'Instant Noodles',
            1102 => 'Oatmeal',
        ];
        
        $meat = [
            1200 => 'Angus Beef Rib',
            1201 => 'Beef',
            1202 => 'Chiecken',
            1203 => 'Fresh Chicken Drumsticks',
            1204 => 'Fresh Chicken Thigh',
            1205 => 'Fresh Chicken Wings',
            1206 => 'Fresh Whole Chicken',
            1207 => 'Fresh Turkey Drumsticks',
            1208 => 'Fresh Turkey Thigh',
            1209 => 'Fresh Turkey Wings',
            1210 => 'Fresh Whole Turkey',
            1211 => 'Ground Beef',
            1212 => 'Ground Pork',
            1213 => 'Prime Rib Steak Beef',
            1214 => 'Sirloin Beef',
            1215 => 'Pork',
            1216 => 'Pork Loin',
            1217 => 'Pork Chops',
            1218 => 'Turkey',
        ];
        
        $pet = [
            1300 => 'Cat Food',
            1301 => 'Cat litter',
            1302 => 'Cat Treats',
            1303 => 'Dog Food',
            1304 => 'Dog Treats', 
            1305 => 'Flea Treatment',
            1306 => 'Pet Shampoo',
        ];
        
        $personal = [
            1400 => 'Adult Diapers / Pullups',
            1401 => 'Adult Leak Guards',
            1402 => 'Antiperspirant / Deodorant', 
            1403 => 'Bath soap',
            1404 => 'Body Lotion',
            1405 => 'Hand soap', 
            1406 => 'Condoms / Other b.c.', 
            1407 => 'Cosmetics',
            1408 => 'Cotton swabs / Balls', 
            1409 => 'Facial Cleanser', 
            1410 => 'Floss',
            1411 => 'Hair Conditioner',
            1412 => 'Hair gel',
            1413 => 'Hair Spray',
            1414 => 'Hand Lotion',
            1415 => 'Lip balm',
            1416 => 'Mouthwash',
            1417 => 'Razors',
            1418 => 'Shaving Cream', 
            1419 => 'Shampoo',
            1420 => 'Sunblock',
            1421 => 'Tampons',
            1422 => 'Toothpaste',
        ];
        
        $pharmacy = [
            1500 => 'Advil / Ibuprofen', 
            1501 => 'Allergy',
            1502 => 'Aspirin',
            1503 => 'Antacid',
            1504 => 'Band-aids',
            1505 => 'Medical Bandages',
            1506 => 'Cough Syrups - Dry',
            1507 => 'Cough Syrups - Phlegm',
            1508 => 'Cold Medicine',
            1509 => 'Diarrhea',
            1510 => 'Flu Medicince',
            1511 => 'Medical Tapes',
            1512 => 'Pain Reliever Medicine',
            1513 => 'Pepto Bismol / Bismuth Subsalicylate',
            1514 => 'Probiotics',
            1515 => 'Sinus Medicine',
            1516 => 'Tylenol / Acetaminophen',
            1517 => 'Vitamins / Supplements',
        ];
        
        $seafood = [
            1600 => 'Catfish',
            1601 => 'Crab',
            1602 => 'Lobster',
            1603 => 'Mussels',
            1604 => 'Oysters',
            1605 => 'Salmon',
            1606 => 'Shrimp',
            1607 => 'Tilapia',
            1608 => 'Tuna',
        ];
        
        $snacks = [
            1700 => 'Candy / Gum',
            1701 => 'Chip dip',
            1702 => 'Cookies',
            1703 => 'Crackers',
            1704 => 'Dried fruit',
            1705 => 'Granola bars / Mix',
            1706 => 'Hummus',
            1707 => 'Nuts / Seeds',
            1708 => 'Popcorn',
            1709 => 'Potato chips',
            1710 => 'Corn chips',
            1711 => 'Pretzels',
        ];
        
        $spices = [
            1800 => 'Basil',
            1801 => 'Black pepper',
            1802 => 'Cilantro',
            1803 => 'Cinnamon',
            1804 => 'Currey Powder',
            1805 => 'Garlic',
            1806 => 'Ginger',
            1807 => 'Masala Powder',
            1808 => 'Mint',
            1809 => 'Oregano',
            1810 => 'Paprika',
            1811 => 'Parsley',
            1812 => 'Red pepper',
            1813 => 'Salt',
            1814 => 'Spice mix',
            1815 => 'Sugar',
            1816 => 'Vanilla extract',
            1817 => 'White Pepper',
        ];
        
        $veggis = [
            1900 => 'A Choy',
            1901 => 'Acorn Squash',
            1902 => 'Asparagus',
            1903 => 'Baby Bok Choy',
            1904 => 'Bean Sprouts',
            1905 => 'Bok Choy',
            1906 => 'Broccoli',
            1907 => 'Butternut Squash',
            1908 => 'Cai Choy',
            1909 => 'Carrots',
            1910 => 'Cauliflower',
            1911 => 'Celery',
            1912 => 'Chinese Bitter Melons',
            1913 => 'Chinese Broccoli / Gai Lan',
            1914 => 'Chinese Cabbage',
            1915 => 'Chinese Carrots',
            1916 => 'Chinese Long Beans / Bodie',
            1917 => 'Corn',
            1918 => 'Cucumbers',
            1919 => 'Dasheen',
            1920 => 'Eggplants',
            1921 => 'Jicama',
            1922 => 'Karela',
            1923 => 'Lettuce',
            1924 => 'Ginger',
            1925 => 'Greens',
            1926 => 'Japanese Yam',
            1927 => 'Kabocha Squash',
            1928 => 'Mushrooms',
            1929 => 'Okra',
            1930 => 'Ong Choy',
            1931 => 'Onions',
            1932 => 'Peppers',
            1933 => 'Potatoes',
            1934 => 'Shanghai Bok Choy',
            1935 => 'Snow Peas',
            1936 => 'Spinach',
            1937 => 'Spaghetti Squash',
            1938 => 'Squash',
            1939 => 'Yam',
            1940 => 'Yellow Yam',
            1941 => 'Taiwanese Cabbage',
            1942 => 'Taro',
            1943 => 'Tomatoes',
            1944 => 'White Yam',
            1945 => 'Yu Choy',
            1946 => 'Yu Choy Sum',
            1947 => 'Zucchini',
        ];
        
        $others = [
            2000 => 'Others',
        ];
        
        $categories = [
            1 => $baby,
            2 => $bakedGoods,
            3 => $beverages,
            4 => $canned,
            5 => $cleaning,
            6 => $condiments,
            7 => $deli,
            8 => $diary,
            9 => $fruits,
            10 => $frozen,
            11 => $household,
            12 => $instantFood,
            13 => $meat,
            14 => $pet,
            15 => $personal,
            16 => $pharmacy,
            17 => $seafood,
            18 => $snacks,
            19 => $spices,
            20 => $veggis,
            21 => $others,
        ];
        
        \DB::table('items')->insert(            
            array_reduce(
                array_keys($categories), 
                function($completeArray, $categoryKey) use ($categories) {
                    if (null === $completeArray) {
                        $completeArray = [];
                    }
                    return array_merge(
                        $completeArray, 
                        array_map(
                            function($categoryItem, $itemKey) use($categoryKey) {
                                return [
                                    'id' => $itemKey,
                                    'name' => $categoryItem,
                                    'item_category_id' => $categoryKey
                                ];
                            }, 
                            $categories[$categoryKey], 
                            array_keys($categories[$categoryKey])
                        )
                    );
                }
            )
        );
    }
}

/**
 *   
 
    */