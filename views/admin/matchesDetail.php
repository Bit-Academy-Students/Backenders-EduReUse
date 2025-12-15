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

// get match
$sql = "SELECT * FROM matches WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);

$match = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$match) {
    http_response_code(404);
    header('location: /admin/matches');
    exit();
}

// get logs
$sql = "SELECT
    history_logs.id,
    history_logs.notitie,
    history_logs.date_created,
    users.naam
FROM history_logs
INNER JOIN users ON history_logs.admin_id = users.id
INNER JOIN matches ON history_logs.match_id = matches.id
WHERE matches.id = :id
ORDER BY history_logs.date_created DESC";

$stmt = $conn->prepare($sql);
$stmt->execute(['id' => $id]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get statuses
$sql = "SELECT * FROM statuses";
$stmt = $conn->prepare($sql);
$stmt->execute();

$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match details</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <div class="w-full max-w-[80%]">
            <div class="flex flex-col gap-10 bg-white p-6 rounded-lg m-5 shadow-lg ">
                <!-- terug knop -->
                <div>
                    <div class="flex flex-row justify-between pb-4 mb-6 border-b border-gray-300">
                        <a href="/admin/matches"
                            class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300 transition-colors w-fit">
                            <i class="fas fa-arrow-left mr-2"></i>Terug
                        </a>
                        <h1 class="font-bold text-3xl">Match overzicht</h1>
                        <button type="button"
                            class="p-2 bg-sky-500 rounded hover:bg-sky-400 text-white transition-colors w-fit cursor-pointer">
                            Genereer PDF
                        </button>
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

            <?php if ($logs) : ?>
                <div class="flex flex-row">
                    <div class="flex flex-col w-2/3 bg-white p-6 rounded-lg m-5 shadow-lg gap-4 h-fit">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-600">Wijzig status /</h2>
                            <h2 class="text-2xl font-bold text-slate-600">Voeg een log toe</h2>
                        </div>
                        <form method="post"
                            class="flex flex-col gap-4 border-t-1 border-gray-200">
                            <div class="flex flex-row gap-2 items-baseline">
                                <label for="status" class="text-slate-500 font-semibold italic cursor-pointer">(Optioneel)</label>
                                <select name="status" id="status"
                                    class="mt-5 bg-white rounded-md p-2 shadow-sm border border-gray-200">
                                    <?php foreach ($statuses as $status) : ?>
                                        <option value="<?= $status['id'] ?>"
                                            <?php if ($status['id'] === $match['status_id']) : ?>
                                            selected
                                            <?php endif; ?>>
                                            <?= $status['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <textarea name="new-log" id="new-log"
                                cols="25" rows="4"
                                class="resize-y px-4 py-2 bg-[#fcfcfc] border border-gray-300 rounded-md shadow-sm"
                                placeholder="Schrijf hier uw logbericht..."></textarea>
                            <input type="submit" value="Verstuur"
                                class="bg-sky-500 text-white rounded-md p-1.5 w-fit hover:bg-sky-400 cursor-pointer transition self-end">
                            <input type="hidden" name="original-match-status" value="<?= $match['status_id'] ?>">
                            <input type="hidden" name="match-id" value="<?= $id ?>">
                        </form>
                        <?php if (isset($_SESSION['error'])) : ?>
                            <p class="font-bold text-xl p-3 rounded-md bg-red-300 text-red-600 w-fit">
                                <?= $_SESSION['error'] ?>
                            </p>
                            <?php unset($_SESSION['error']); ?>
                        <?php endif; ?>
                    </div>

                    <div class="flex flex-col justify-self-center gap-10 bg-white p-6 rounded-lg m-5 shadow-lg w-full max-w-2xl h-fit">
                        <div>
                            <h2 class="font-bold text-slate-600 text-3xl pb-3 border-b border-gray-300">
                                Logs
                            </h2>
                            <div class="flex flex-col gap-4">
                                <?php foreach ($logs as $log) : ?>
                                    <div class="flex flex-row gap-4 items-baseline border-b border-gray-200 py-4">
                                        <div class="min-w-32">
                                            <p class="text-xl font-bold text-sky-500"><?= ucfirst($log['naam']) ?></p>
                                            <?php $exp = explode(' ', $log['date_created']); ?>
                                            <p class="text-sm text-gray-500">Datum: <?= $exp[0] ?></p>
                                            <p class="text-sm text-gray-500">Tijd: <?= $exp[1] ?></p>
                                        </div>
                                        <p class="text-lg text-gray-700 italic"><?= $log['notitie'] ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>