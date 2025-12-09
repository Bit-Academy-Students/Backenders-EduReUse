<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE ". $db->getDbName());

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);

$user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once 'components/header.php' ?>

    <div class="flex flex-col bg-white rounded-lg p-6 my-15 justify-self-center w-[60%] shadow-lg gap-10">
        <div class="flex flex-col gap-2">
            <h1 class="font-bold text-2xl">Mijn aanbiedingen</h1>
            <div class="flex flex-row items-center gap-4">
                <p class="font-semibold text-slate-500">Je hebt nog geen aanbiedingen geplaatst...</p>
                <a href="/doneer"
                class="rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white w-26 cursor-pointer hover:bg-sky-500 transition">
                Doneer hier
                </a>
            </div>
        </div>
        
        <div class="flex flex-col gap-2">
            <h1 class="font-bold text-2xl">Mijn aanvragen</h1>
            <div class="flex flex-row items-center gap-4">
                <p class="font-semibold text-slate-500">Je hebt nog geen aanvragen geplaatst...</p>
                <a href="/aanvraag"
                    class="rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white w-45 cursor-pointer hover:bg-sky-500 transition">
                    Vraag een product aan
                </a>
            </div>
        </div>
    </div>

</body>