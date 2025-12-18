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
<header class="bg-[#5481B7] px-26 flex justify-between items-center">
    <div>
        <a href="/" class="text-white">
            <img src="/uploads/header-banner-foundation.png" alt="Foundation logo"
                class="w-90 h-auto">
        </a>
    </div>

    <div class="flex gap-8">
        <div class="flex flex-row gap-4">
            <a href="<?= isset($_SESSION['id']) ? '/doneer' : '/login' ?>" class=" text-white rounded-md p-1.5 w-35 mt-1 hover:bg-sky-600 transition text-center w-fit h-fit">Donatie doen</a>
            <a href="<?= isset($_SESSION['id']) ? '/aanvraag' : '/login' ?>" class=" text-white rounded-md p-1.5 w-35 mt-1 hover:bg-sky-600 transition text-center w-fit">Aanvraag indienen</a>
        </div>

        <?php if ($userIsAdmin) { ?>
            <a href="/admin/aanvragen" class="text-2xl text-[#DDE6F1] hover:text-white transition"><i class="fa-brands fa-black-tie"></i></a>
        <?php } ?>

        <?php if ($loggedInUser) { ?>
            <a href="/user/posts" class="text-2xl text-[#DDE6F1] hover:text-white transition"><i class="fa-solid fa-envelopes-bulk"></i></a>
        <?php } ?>

        <?php if (!$loggedInUser) { ?>
            <a href="/login" class="text-2xl text-[#DDE6F1] hover:text-white transition"><i class="fa-solid fa-user"></i></a>
        <?php } ?>

        <?php if ($loggedInUser) { ?>
            <a href="/user" class="text-2xl text-[#DDE6F1] hover:text-white transition"><i class="fa-solid fa-user"></i></a>
            <a href="/logout" class="text-2xl text-[#DDE6F1] hover:text-white transition"><i class="fa-solid fa-right-from-bracket"></i></a>
        <?php } ?>
    </div>
</header>