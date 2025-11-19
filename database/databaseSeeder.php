<?php

use Database\Database;
use Database\seeders\TypeSeeder;
use Database\seeders\UserSeeder;

require_once __DIR__ . '/../vendor/autoload.php';

$db = new Database()->getConnection();

// add test users + admin
$userSeeder = new UserSeeder($db);
$userSeeder->truncate('users');
$userSeeder->addUser('test', 'test', 'test', 0);
$userSeeder->addUser('Donor', 'donor@school.nl', 'donor', 0);
$userSeeder->addUser('Need', 'need@school.nl', 'need', 0);
$userSeeder->addUser('admin', 'admin', 'admin', 1);

// add types
$typeSeeder = new TypeSeeder($db);
$userSeeder->truncate('types');
$typeSeeder->addType('Laptops');
$typeSeeder->addType('3d-printers');
$typeSeeder->addType('Robots');
