<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// get type from database
$sql = "SELECT * FROM types WHERE id = :typeId";
$stmt = $conn->prepare($sql);
$stmt->execute(['typeId' => $_GET['type_id']]);

$type = $stmt->fetch(PDO::FETCH_ASSOC);

// get need from database
$sql = "SELECT needs.id, needs.titel, needs.hoeveelheid, needs.postcode, needs.deadline, needs.date_created, needs.date_modified, types.type, users.naam
FROM `needs`
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
WHERE needs.id = :need_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['need_id' => $_GET['need_id']]);

$need = $stmt->fetch(PDO::FETCH_ASSOC);

// send user back if no offers are selected
if (!isset($_GET['selected_offers'])) {
    sendBackWithSessionError($need);
}

// get offers with correct type from database
$sql = "SELECT offers.id, offers.titel, product_states.label, offers.hoeveelheid, offers.beschrijving, offers.postcode, offers.date_created, offers.date_modified, types.type, users.naam
FROM `offers`
INNER JOIN product_states ON offers.staat_id = product_states.id
INNER JOIN types ON offers.type_id = types.id
INNER JOIN users ON offers.user_id = users.id
WHERE offers.id = :offerId
ORDER BY offers.id DESC";
$stmt = $conn->prepare($sql);

// store offer(s)
$offers = [];

$amount = 0;
if (!is_array($_GET['selected_offers'])) {
    $stmt->execute(['offerId' => $_GET['selected_offers']]);
    $offers = $stmt->fetch(PDO::FETCH_ASSOC);
    $amount = $offers['hoeveelheid'];
} else {
    $offers = [];
    foreach ($_GET['selected_offers'] as $offerId) {
        $stmt->execute(['offerId' => $offerId]);
        $offers[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    foreach ($offers as $offer) {
        $amount += $offer['hoeveelheid'];
    }
}

// if no offers are selected, send user back
$i = 0;
foreach ($offers as $offer) {
    $i += $offer['hoeveelheid'];
    if ($i > $need['hoeveelheid'] || $i == 0) {
        sendBackWithSessionError($need, 'Het aantal gekozen aanbiedingen is meer dan de gevraagde hoeveelheid');
    }
}

// get statuses from database
$sql = "SELECT * FROM statuses";
$stmt = $conn->prepare($sql);
$stmt->execute();
$statuses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// check if need is completely fulfilled
$isFulfilled = ($need['hoeveelheid'] === $amount) ? 1 : 0;

/**
 * Sends user back to previous page
 * @param array $need from the database
 * @param ?string|null $errorMessage
 */
function sendBackWithSessionError(array $need, ?string $errorMessage = null)
{
    if (!empty($errorMessage)) {
        $_SESSION['error'] = $errorMessage;
    }

    header('location: /admin/ready-to-match/' . $need['id'] . '/' . $need['type']);
    exit();
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <h1 class="text-3xl justify-self-center font-bold mt-8">
        Verifieer en bevestig match
    </h1>

    <form method="post">
        <?php set_csrf(); ?>
        <div class="grid grid-cols-1 md:grid-cols-11 justify-self-center w-9/10 gap-4">
            <div class="flex flex-col bg-white rounded-lg shadow-md justify-self-center w-full my-10 px-6 pt-6 md:col-span-5">
                <h1 class="font-bold text-3xl text-center mb-4">
                    <?= count($offers) ?> aanbieding<?= (count($offers) > 1) ? 'en' : '' ?>
                </h1>
                <div class="flex flex-col *:text-lg">
                    <?php foreach ($offers as $offer) { ?>
                        <div class="border-t-1 border-gray-200 py-4 *:my">
                            <p class="text-center text-2xl font-semibold"><?= $offer['titel'] ?></p>
                            <p><b>Aantal</b> <?= $offer['hoeveelheid'] ?></p>
                            <p><b>Staat</b> <?= $offer['label'] ?></p>
                            <p><b>Aanbieder</b> <?= $offer['naam'] ?></p>
                            <p><b>Ophaalpostcode</b> <?= $offer['postcode'] ?></p>
                            <input type="hidden" name="offers[]" value="<?= $offer['id'] ?>">
                        </div>
                    <?php } ?>
                </div>
            </div>

            <i class="fa-solid fa-right-long col-span-1 justify-self-center md:mt-30 text-3xl"></i>

            <div class="flex flex-col bg-white rounded-lg shadow-md justify-self-center w-full h-fit my-10 p-6 md:col-span-5">
                <h1 class="font-bold text-3xl text-center mb-4">
                    Aanvraag
                </h1>
                <div class="flex flex-col border-t-1 border-gray-200 pt-4 *:my">
                    <p class="text-center text-2xl font-semibold"><?= $need['titel'] ?></p>
                    <p class="text-lg"><b>Type</b> <?= $need['type'] ?></p>
                    <p class="text-lg"><b>Aantal nodig</b> <?= $need['hoeveelheid'] ?></p>
                    <p class="text-lg"><b>Aflever postcode</b> <?= $need['postcode'] ?></p>
                </div>
                <?php if ($amount !== $need['hoeveelheid']) { ?>
                    <div class="border-t-1 border-gray-200 pt-2 mt-2">
                        <p class="text-gray-400">
                            Na de match heeft <?= $need['naam'] ?> <b class="text-gray-600">nog <?= $need['hoeveelheid'] - $amount ?> <?= $need['type'] ?></b> nodig
                        </p>
                    </div>
                <?php } ?>
            </div>
        </div>

        <!-- verificatie -->
        <h1 class="text-3xl justify-self-center font-bold">
            Klaar om te matchen?
        </h1>

        <div class="justify-self-center justify-center flex flex-col mb-35 gap-4 bg-white rounded-lg w-full md:w-4/10 p-4 shadow-md mt-4">
            <div class="flex flex-col p-2 gap-2">
                <label for="status" class="text-xl font-semibold cursor-pointer">
                    Status
                </label>
                <select name="status" id="status" class="bg-white rounded-md p-2 shadow-sm border border-gray-200">
                    <?php foreach ($statuses as $status) { ?>
                        <option value="<?= $status['id'] ?>" <?= ($status['id'] === 3) ? 'selected' : '' ?>>
                            <?= $status['label'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="flex flex-col p-2 gap-2">
                <label for="log" class="text-xl font-semibold cursor-pointer">
                    Log
                </label>
                <textarea name="log" id="log" cols="10" rows="4"
                    class="resize-y px-4 py-2 bg-[#fcfcfc] border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-500"
                    required
                    placeholder="Bijvoorbeeld: Klant heeft het product zelf opgehaald, omdat hij om de hoek woont. De status heb ik gelijk op 'Geleverd' gezet.
-Admin"></textarea>
            </div>

            <div class="justify-center flex flex-row p-2 gap-2 items-center">
                <label for="confirmed" class="text-xl font-semibold cursor-pointer">
                    Klopt alles?
                </label>
                <input type="checkbox" name="confirmed" id="confirmed" class="w-4 h-4" required>
            </div>
            <?php if (isset($_SESSION['error'])) { ?>
                <p class="font-bold text-xl p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']); ?>
            <?php } ?>
        </div>

        <!-- match button footer -->
        <footer class="flex gap-5 items-center justify-center fixed bottom-0 bg-slate-200 w-full p-2">
            <a href="<?= '/admin/ready-to-match/' . $need['id'] . '/' . $need['type'] ?>"
                class="text-gray-600 hover:text-black font-semibold transition">
                <i class="fa-solid fa-backward"></i>
                Terug
            </a>

            <input type="hidden" name="need_id" value="<?= $need['id'] ?>">
            <input type="hidden" name="previous-url" value="<?= $_SERVER['REQUEST_URI'] ?>">
            <input type="hidden" name="need-fulfilled" value="<?= $isFulfilled ?>">
            <button type="submit"
                id="submit"
                disabled
                class="bg-sky-500 font-semibold text-white px-3 py-2 hover:bg-sky-600 rounded-md transition disabled:cursor-default cursor-pointer disabled:bg-slate-400 disabled:hover:bg-slate-500 disabled:text-white">
                Match
            </button>
        </footer>
    </form>


    <script>
        const confirmedBtn = document.getElementById('confirmed');
        const submitBtn = document.getElementById('submit');
        confirmedBtn.addEventListener('change', (e) => {
            if (confirmedBtn.checked) {
                submitBtn.disabled = false;
            }
            if (!confirmedBtn.checked) {
                submitBtn.disabled = true;
            }
        });
    </script>
</body>

</html>