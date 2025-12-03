<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());


?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanbod</title>
    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/components/fontawesome-link.php' ?>
</head>

<body>
    <header class="bg-sky-300 p-3">
        <div>
            <div class="flex flex-row-end">
                <p>LOGO</p>
            </div>
            <nav class="flex flex-row-reverse">
                <a class="flex bg-white rounded-lg w-30 justify-center items-center" href="/logout">afmelden</a>
                <a class="flex justify-center items-center" href="#">Mijn aanvragen</a>
                <a class="flex justify-center items-center" href="#">Mijn aanbod</a>
            </nav>
        </div>
    </header>

    <div class="bg-sky-100 h-screen">
        <div class="w-96 p-6 shadow-lg bg-white rounded-lg h-100">
            <img src="#" alt="apparaat afbeelding">

            <p>Status</p>
        </div>

    </div>