<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

session_start();

?>

<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="style.css">
    </head>
        <body class="home-body">
            <header class="home-header">
                <div class="wrapper">
                    <div class="home-logo">
                        <a href="index.php">LOGO</a>
                    </div>
                    <nav class="home-nav">
                        <a href="logout.php">Afmelden</a>
                    </nav>
                </div>
            </header>

