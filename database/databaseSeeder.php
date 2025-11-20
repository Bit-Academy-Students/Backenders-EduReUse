<?php

use Database\Database;
use Database\seeders\TypeSeeder;
use Database\seeders\UserSeeder;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database('edureuse');

// add test users + admin
$userSeeder = new UserSeeder($db->connect());
$userSeeder->truncate('users');
$userSeeder->addUser('test', 'test', 'test', 0);
$userSeeder->addUser('Donor', 'donor@school.nl', 'donor', 0);
$userSeeder->addUser('Need', 'need@school.nl', 'need', 0);
$userSeeder->addUser('admin', 'admin', 'admin', 1);
echo 'Users added' . PHP_EOL;

// add types
$typeSeeder = new TypeSeeder($db->connect());
$userSeeder->truncate('types');
$typeSeeder->addType('Laptops');
$typeSeeder->addType('3d-printers');
$typeSeeder->addType('Robots');
echo 'Types added' . PHP_EOL;
