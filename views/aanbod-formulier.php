<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$id = (int) $_GET['id'];

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$recordset = $stmt->execute(['id' => $id]);

$sql2 = "SELECT * FROM offers WHERE user_id = :user_id";
$stmt2 = $conn->prepare($sql2);
$recordset2 = $stmt2->execute(['user_id' => $id]);


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
                <a class="flex bg-white rounded-lg w-30 justify-center items-center" href="logout.php">afmelden</a>
                <a class="flex justify-center items-center" href="../views/aanvraag.php?id=<?=$id;?>"> Mijn aanvragen</a>
                <a class="flex justify-center items-center" href="../views/aanbod.php?id=<?=$id;?>"> Mijn aanbod</a>
            </nav>
        </div>
    </header>

    <div class="bg-sky-100 h-screen w-full flex">

        <a href="formulier-donor.php?id=<?=$id?>">Nieuw apparaat aanbieden</a>

        <?php while ($offer = $stmt2->fetch()) : ?>
        <div class="mt-10 w-96 p-6 shadow-lg bg-white rounded-lg h-100">
            <img src="<?= $offer['image_url'] ?>" alt="apparaat afbeelding" class="w-40">
            <h2 class="font-bold text-2xl"><?= $offer['titel'] ?></h2>
            <p>Status</p>
        </div>
         <?php endwhile; ?>

    </div>