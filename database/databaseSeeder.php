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

$db = new Database('');

// seeders + data
$seeders = [
    UserSeeder::class => [
        'truncate' => 'users',
        'data' => [
            ['test', 'test', 'test'],
            ['Donor', 'donor@school.nl', 'donor'],
            ['Need', 'need@school.nl', 'need'],
            ['admin', 'admin', 'admin', 1],
        ],
    ],
    TypeSeeder::class => [
        'truncate' => 'types',
        'data' => [
            ['Laptops'],
            ['3d-printers'],
            ['Robots'],
        ],
    ],
    StatusSeeder::class => [
        'truncate' => 'statuses',
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
        'truncate' => 'product_states',
        'data' => [
            ['Nieuw'],
            ['Gebruikt'],
            ['Beschadigd'],
            ['Niet werkend'],
        ],
    ],
    OfferSeeder::class => [
        'truncate' => 'offers',
        'data' => [
            ['50x50cm printer', 1, 4, 'Printer werkt zoals verwacht', '1053 VL', 2, 2],
            ['Apple Macbook Air M3', 2, 1, 'mooi ding', '1053 VL', 1, 2],
            ['Apple Macbook Air M3', 3, 1, 'mooi ding', '1053VL', 1, 2], // wrong postcode input test
        ],
    ],
    NeedSeeder::class => [
        'truncate' => 'needs',
        'data' => [
            ['Snel werkende laptop', 1, '1011 AC', '2025-12-17', 1, 3],
            ['3D-printer', 2, '1011 AC', '2025-12-17', 2, 3],
            ['Zelfrijdende robot', 1, '1011 AC', '2025-12-17', 3, 3],
            ['Zelfrijdende robot', 1, '1011AC', '2025-12-17', 3, 3], // wrong postcode input test
            ['Zelfrijdende robot', 1, '1011 AC', '2025-12-217', 3, 3], // wrong deadline input test
        ],
    ],
    MatchSeeder::class => [
        'truncate' => 'matches',
        'data' => [
            [1, 1, 3],
        ],
    ],
    HistoryLogsSeeder::class => [
        'truncate' => 'history_logs',
        'data' => [
            ["Ik heb de status van deze match van \'nieuw\' naar \'gematched\' gewijzigd", 4, 1],
        ],
    ],
];

echo '------------------------------------------------------------------' . PHP_EOL;

// Run seeders
foreach ($seeders as $seederClass => $config) {
    $seeder = new $seederClass($db->connect());
    $seeder->truncate($config['truncate']);
    foreach ($config['data'] as $data) {
        try {
            $seeder->add(...$data);
        } catch (Exception $e) {
            echo PHP_EOL . '[WARNING]: '. $e->getMessage() . PHP_EOL;
        }
    }

    echo ucfirst($config['truncate']) . ' seeded' . PHP_EOL;
}
