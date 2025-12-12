<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// check if user is admin
$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $_SESSION['id'],
]);

$user = $stmt->fetch();
if (!$user['is_admin']) {
    http_response_code(403);
    header('location: /404');
    exit();
}

$sql = "SELECT
    history_logs.id,
    history_logs.notitie,
    history_logs.date_created,
    users.naam
FROM history_logs
INNER JOIN users ON history_logs.admin_id = users.id
INNER JOIN matches ON history_logs.match_id = matches.id
WHERE matches.id = :id";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// echo '<pre>';
// echo var_dump($logs);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match details - <?= htmlspecialchars($need['titel']) ?></title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="w-full max-w-4xl">
            <div class="flex flex-col gap-10 bg-white p-6 rounded-lg m-5 shadow-lg max-w-4xl">
                <!-- terug knop -->
                <div>
                    <div class="grid grid-cols-3 flex gap-10 pb-4 mb-6 border-b border-gray-300">
                        <a href="/admin/matches"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors w-fit">
                            <i class="fas fa-arrow-left mr-2"></i>Terug
                        </a>
                        <h1 class="font-bold text-3xl">Match overzicht</h1>
                    </div>

                    <!-- Need informatie -->
                    <div class="space-y-4">

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Gebruiker</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Titel</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Hoeveelheid</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Product Type</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Postcode</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Deadline</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>

                        <div class="border-b border-gray-200 pb-3">
                            <label class="text-sm font-semibold text-gray-600 uppercase">Datum gecreëerd</label>
                            <p class="text-lg text-gray-800"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col justify-self-center gap-10 bg-white p-6 rounded-lg m-5 shadow-lg w-full max-w-2xl">
                <?php if ($logs) : ?>
                    <div>
                        <h2 class="font-bold text-3xl pb-3">
                            Logs
                            <!-- <span class="text-sm text-gray-300">Lorem ipsum dolor sit amet consectetur adipisicing elit. Harum qui aspernatur consequuntur beatae?</span> -->
                        </h2>
                        <div class="flex flex-col gap-4 border-t-1 pt-4 border-gray-200">
                            <?php foreach ($logs as $log) : ?>
                                <div class="flex flex-row gap-4 items-baseline">
                                    <p class="text-xl font-bold"><?= ucfirst($log['naam']) ?></p>
                                    <p class="text-lg"><?= $log['notitie'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>