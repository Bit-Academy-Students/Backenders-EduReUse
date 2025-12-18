<?php

use Database\Database;
use Database\seeders\UserSeeder;
use Database\seeders\TypeSeeder;
use Database\seeders\StatusSeeder;
use Database\seeders\ProductStateSeeder;
use Database\seeders\OfferSeeder;
use Database\seeders\NeedSeeder;
use Database\seeders\MatchSeeder;
use Database\seeders\HistoryLogsSeeder;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database();

// seeders + data
$seeders = [
    UserSeeder::class => [
        'table' => 'users',
        'data' => [
            ['test', 'test', 'test'],
            ['Donor', 'donor@school.nl', 'donor'],
            ['Need', 'need@school.nl', 'need'],
            ['admin', 'admin', 'admin', 1],
            ['demo', 'demo', 'demo'],
        ],
    ],
    TypeSeeder::class => [
        'table' => 'types',
        'data' => [
            ['Laptops'],
            ['3d-printers'],
            ['Robots'],
        ],
    ],
    StatusSeeder::class => [
        'table' => 'statuses',
        'data' => [
            ['Nieuw'],
            ['In verificatie'],
            ['Gematched'],
            ['Ophalen gepland'],
            ['Opgehaald'],
            ['Refurbish'],
            ['Geleverd'],
            ['Afgerond'],
        ],
    ],
    ProductStateSeeder::class => [
        'table' => 'product_states',
        'data' => [
            ['Nieuw'],
            ['Gebruikt'],
            ['Beschadigd'],
            ['Niet werkend'],
        ],
    ],
    OfferSeeder::class => [
        'table' => 'offers',
        'data' => [
            ['50x50cm printer', 1, 4, 'Printer werkt zoals verwacht', '3318 VP', 2, 2, 'https://tweakers.net/pricewatch/1033703/canon-pixma-ts5150-zwart.html?utm_source=google&utm_medium=css&utm_campaign=organic', 'default_printer_image.png'],
            ['Apple Macbook Air M3', 2, 1, 'mooi ding', '1949 BA', 1, 2, 'https://www.mediamarkt.nl/nl/product/_apple-macbook-air-2024-spacegrijs-136-inch-apple-m3-10-core-gpu-16-gb-512-gb-1875678.html?utm_source=google&utm_medium=cpc&utm_campaign=rt_shopping_generic_nsp_nl-IT-&-Gaming-Laptops&utm_term=&utm_content=TCID21718438714-TAID176149451478&gad_source=1&gad_campaignid=21718438714&gbraid=0AAAAADoGBO6tyLrsf_5qMgRREWR6HuNc2', 'default_laptop_image.png'],
            ['3D-printer Ultimaker', 1, 2, 'Professionele 3D-printer', '1234 AB', 3, 5, 'https://example.com/3d-printer', 'default_printer_image.png'],
            ['Gaming Laptop', 2, 1, 'High-end gaming laptop', '5678 CD', 1, 5, 'https://example.com/gaming-laptop', 'default_new_laptop.png'],
            ['Gaming Laptop', 2, 1, 'High-end gaming laptop', '5678 CD', 1, 5, 'https://example.com/gaming-laptop', 'default_new_laptop.png'],
            ['Refurbished 3d printers', 3, 1, 'Refurbished printers in goede staat', '9101 EF', 3, 5, 'https://example.com/refurbished-iphone', 'default_new_printer_image.jpg'],
            ['Refurbished 3d printers', 3, 4, 'Refurbished printers in goede staat', '9101 EF', 3, 5, 'https://example.com/refurbished-iphone', 'default_new_printer_image.jpg'],
        ],
    ],
    NeedSeeder::class => [
        'table' => 'needs',
        'data' => [
            ['Snel werkende laptop', 4, '1623 RV', '2025-12-17', 1, 3],
            ['3D-printer', 2, '3022 EV', '2025-12-17', 2, 3],
            ['Zelfrijdende robot', 7, '1062 HG', '2025-12-17', 3, 3],
            ['Zelfrijdende robot', 1, '7681 MD', '2025-12-17', 3, 3, 1],
            ['Laptop voor school', 3, '1234 AB', '2025-12-20', 1, 5],
            ['3D-printer voor projecten', 2, '5678 CD', '2025-12-22', 2, 5],
            ['Robotica kit', 7, '9101 EF', '2025-12-25', 3, 5],
            ['Refurbished laptops', 5, '7890 IJ', '2026-01-05', 1, 5],
        ],
    ],
    MatchSeeder::class => [
        'table' => 'matches',
        'data' => [
            [1, 1, 3],
        ],
    ],
    HistoryLogsSeeder::class => [
        'table' => 'history_logs',
        'data' => [
            ["Ik heb de status van deze match van \'nieuw\' naar \'gematched\' gewijzigd", 4, 1],
        ],
    ],
];

echo '-----------------------------------------------------------------' . PHP_EOL;

// Run seeders
foreach ($seeders as $seederClass => $config) {
    $seeder = new $seederClass($db->connect());
    $seeder->truncate($config['table']);
    foreach ($config['data'] as $data) {
        try {
            $seeder->add(...$data);
        } catch (Exception $e) {
            echo PHP_EOL . '[WARNING]: '. $e->getMessage() . PHP_EOL;
        }
    }

    echo ucfirst($config['table']) . ' seeded' . PHP_EOL;
}
