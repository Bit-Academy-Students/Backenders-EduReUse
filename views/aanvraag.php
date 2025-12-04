<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

$id = (int) $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$recordset = $stmt->execute(['id' => $id]);

$sql2 = "SELECT * FROM needs WHERE user_id = :user_id";
$stmt2 = $conn->prepare($sql2);
$recordset2 = $stmt2->execute(['user_id' => $id]);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanbod</title>
    <link rel="stylesheet" href="../public/src/output.css">
</head>

<body>
    <header class="bg-sky-300 p-3">
        <div>
            <div class="flex flex-row-end">
                <p>LOGO</p>
            </div>
            <nav class="flex flex-row-reverse">
                <a class="flex bg-white rounded-lg w-30 justify-center items-center" href="logout.php">afmelden</a>
                <a class="flex justify-center items-center" href="../views/aanvraag.php?id=<?=$id;?>"> Mijn aanvragen</a>
                <a class="flex justify-center items-center" href="../views/aanbod.php?id=<?= $id;?>"> Mijn aanbod</a>
            </nav>
        </div>
    </header>

        
        <div class="flex justify-center items-center  pt-10">

            <a href="formulier-need.php?id=<?= $id;?>">Nieuw apparaat aanvragen</a>

             <table class="w-100">
                <caption class="caption-top mb-10">
                    Mijn aanvragen
                </caption>

                <thead class="bg-sky-100 border-b-2 border-sky-700">
                <tr class="">
                    <th class="w-50 p-3 text-sm font-semibold tracking-wide ">Model</th>
                    <th class="w-10 p-3 text-sm font-semibold tracking-wide ">Hoeveelheid</th>
                    <th class="w-30 p-3 text-sm font-semibold tracking-wide ">status</th>
                </tr>
                </thead>

                <tbody>
                <?php while ($need = $stmt2->fetch()) : ?>
                <tr class=" "> 
                    <td class="p-3 text-sm"> <?=$need['titel']?> </td>
                    <td class="p-3 text-sm"> <?=$need['hoeveelheid']?> </td>
                    <td class="p-3 text-sm">Open</td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
         
