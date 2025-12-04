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
$stmt->execute(['typeId' => $_POST['type_id']]);
$type = $stmt->fetch(PDO::FETCH_ASSOC);

// get need from database
$sql = "SELECT needs.id, needs.titel, needs.hoeveelheid, needs.postcode, needs.deadline, needs.date_created, needs.date_modified, types.type, users.naam
FROM `needs`
INNER JOIN types ON needs.type_id = types.id
INNER JOIN users ON needs.user_id = users.id
WHERE needs.id = :need_id";
$stmt = $conn->prepare($sql);
$stmt->execute(['need_id' => $_POST['need_id']]);
$need = $stmt->fetch(PDO::FETCH_ASSOC);

if (!isset($_POST['selected_offers'])) {
    sendBack($need);
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

$offers = '';
if (is_array($_POST['selected_offers'])) {
    $offers = [];
    foreach ($_POST['selected_offers'] as $offerId) {
        $stmt->execute(['offerId' => $offerId]);
        $offers[] = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} else {
    $stmt->execute(['offerId' => $_POST['selected_offers']]);
    $offers = $stmt->fetch(PDO::FETCH_ASSOC);
}

// if no offers are selected, send user back
$i = 0;
foreach ($offers as $offer) {
    $i += $offer['hoeveelheid'];
    if ($i > $need['hoeveelheid'] || $i == 0) {
        sendBack($need, 'De hoeveelheid van de gekozen aanbiedingen is meer dan de gevraagde hoeveelheid');
    }
}

/**
 * Sends user back to previous page
 * @param array $need from the database
 * @param ?string|null $errorMessage
 */
function sendBack(array $need, ?string $errorMessage = null)
{
    if ($errorMessage) {
        $_SESSION['error'] = $errorMessage;
    }

    header('location: /admin/ready-to-match/' . $need['id'] . '/' . $need['type']);
    exit();
}

echo '<pre>';
print_r($offers);
print_r($need);
echo '</pre>';


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Match</title>

    <link rel="stylesheet" href="/../src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <h1>Hello world</h1>
</body>

</html>