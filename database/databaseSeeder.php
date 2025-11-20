<?php

use Database\Database;
use Database\seeders\HistoryLogsSeeder;
use Database\seeders\MatchSeeder;
use Database\seeders\NeedSeeder;
use Database\seeders\OfferSeeder;
use Database\seeders\ProductStateSeeder;
use Database\seeders\StatusSeeder;
use Database\seeders\TypeSeeder;
use Database\seeders\UserSeeder;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database('edureuse');

// add test users + admin
$userSeeder = new UserSeeder($db->connect());
$userSeeder->truncate('users');
$userSeeder->addUser('test', 'test', 'test');
$userSeeder->addUser('Donor', 'donor@school.nl', 'donor');
$userSeeder->addUser('Need', 'need@school.nl', 'need');
$userSeeder->addUser('admin', 'admin', 'admin', 1);
echo 'Users added' . PHP_EOL;

// add types
$typeSeeder = new TypeSeeder($db->connect());
$typeSeeder->truncate('types');
$typeSeeder->addType('Laptops');
$typeSeeder->addType('3d-printers');
$typeSeeder->addType('Robots');
echo 'Types added' . PHP_EOL;

// add statuses
$statusSeeder = new StatusSeeder($db->connect());
$statusSeeder->truncate('statuses');
$statusSeeder->addStatus('Nieuw');
$statusSeeder->addStatus('In verificatie');
$statusSeeder->addStatus('Gematched');
$statusSeeder->addStatus('Ophalen gepland');
$statusSeeder->addStatus('Opgehaald');
$statusSeeder->addStatus('Refurbish');
$statusSeeder->addStatus('Geleverd');
$statusSeeder->addStatus('Afgerond');
echo 'Statuses added' . PHP_EOL;

// add product states
$statusSeeder = new ProductStateSeeder($db->connect());
$statusSeeder->truncate('product_states');
$statusSeeder->addProductState('Nieuw');
$statusSeeder->addProductState('Gebruikt');
$statusSeeder->addProductState('Beschadigd');
$statusSeeder->addProductState('Niet werkend');
echo 'Product states added' . PHP_EOL;

// add offers
$offerSeeder = new OfferSeeder($db->connect());
$offerSeeder->truncate('offers');
$offerSeeder->addOffer('Apple Macbook Air M3', 'gebruikt', 1, 'mooi ding', '1053 VL', 1, 2);
$offerSeeder->addOffer('50x50cm printer', 'nieuw', 4, 'Printer werkt zoals verwacht', '1053 VL', 2, 2);
echo 'Offers added' . PHP_EOL;

// add needs
$needSeeder = new NeedSeeder($db->connect());
$needSeeder->truncate('needs');
$needSeeder->addNeed('Snel werkende laptop', 1, '1011 AC', 1, 3);
$needSeeder->addNeed('3D-printer', 2, '1011 AC', 2, 3);
$needSeeder->addNeed('Zelfrijdende robot', 1, '1011 AC', 3, 3);
echo 'Needs added' . PHP_EOL;

// add matches
$matchSeeder = new MatchSeeder($db->connect());
$matchSeeder->truncate('matches');
$matchSeeder->addMatch(1, 1, 3);
echo 'Matches added' . PHP_EOL;

// add history logs
$historyLogsSeeder = new HistoryLogsSeeder($db->connect());
$historyLogsSeeder->truncate('history_logs');
$historyLogsSeeder->addHistoryLog("Ik heb de status van deze match van \'nieuw\' naar \'gematched\' gewijzigd", 4, 1);
echo 'Statuses added' . PHP_EOL;