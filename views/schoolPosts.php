<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $_SESSION['id']]);

$user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="resources/style.css">
</head>

<body class="home-body">
    <?php require_once 'components/header.php' ?>

    <div class="container">
        <h2>Mijn aanbiedingen</h2>
        <h2>Mijn aanvragen</h2>
    </div>
</body>