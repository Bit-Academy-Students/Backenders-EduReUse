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
</head>

<body class="bg-gray-100">
    <?php require_once 'components/header.php' ?>

    <div class="flex flex-col bg-white rounded-lg p-6 my-15 justify-self-center w-[90%] shadow-lg">
        <h1 class="font-bold text-2xl">Mijn aanbiedingen</h1>
        <h1 class="font-bold text-2xl">Mijn aanvragen</h1>
    </div>
</body>