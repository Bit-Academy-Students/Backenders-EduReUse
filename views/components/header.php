<?php

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$loggedInUser = false;
if (isset($_SESSION['id'])) {
    $stmt->execute(['id' => $_SESSION['id']]);
    $user = $stmt->fetch();

    $loggedInUser = true;
}

$userIsAdmin = false;
if (isset($user) && $user['is_admin'] === 1) {
    $userIsAdmin = true;
}

?>
<header>
    <!-- <a href="/">LOGO</a> -->
    <a href="/">home</a>

    <?php if ($loggedInUser) { ?>
        <a href="/aanvraag">aanvragen</a>
        <a href="/doneer">donaties</a>
        <a href="/school-posts">schoolPosts</a>
    <?php } ?>

    <?php if (!$loggedInUser) { ?>
        <a href="/login">login</a>
        <a href="/register">register</a>
    <?php } ?>


    <?php if ($userIsAdmin) { ?>
        <a href="/admin">admin</a>
    <?php } ?>

    <?php if ($loggedInUser) { ?>
        <a href="/logout">afmelden</a>
    <?php } ?>
</header>