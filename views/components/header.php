<?php

use Database\Database;

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

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
<header class="bg-[#5481B7] px-30 flex justify-between items-center">
    <div>
        <a href="/" class="text-white">
            <img src="/uploads/header-banner-foundation.png" alt="Foundation logo"
                class="h-22">
        </a>
    </div>

    <div class="flex gap-10">
        <?php if ($loggedInUser) { ?>
            <a href="/aanvraag" class="text-white">Aanvragen</a>
            <a href="/doneer" class="text-white">Donaties</a>
            <a href="/school-posts" class="text-white">SchoolPosts</a>
        <?php } ?>

        <?php if (!$loggedInUser) { ?>
            <a href="/login" class="text-white">Login</a>
            <a href="/register" class="text-white">Register</a>
        <?php } ?>


        <?php if ($userIsAdmin) { ?>
            <a href="/admin" class="text-white">Admin</a>
        <?php } ?>

        <?php if ($loggedInUser) { ?>
            <a href="/logout" class="text-white">Afmelden</a>
        <?php } ?>
    </div>
</header>