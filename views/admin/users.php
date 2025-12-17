<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

$sql = "SELECT * FROM users WHERE id != :userId";
$stmt = $conn->prepare($sql);
$stmt->execute(['userId' => $_SESSION['id']]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$isAdminOptions = [
    0 => 'Nee',
    1 => 'Ja'
];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikers</title>

    <link rel="stylesheet" href="/src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>
    <form method="post" class="flex">
        <?php require_once __DIR__ . '/../components/leftSidebar.php' ?>

        <?php set_csrf(); ?>
        <div class="flex flex-col gap-5 w-1/1 m-5">
            <div class="bg-white p-6 rounded-lg shadow-lg w-full">
                <h1 class=" ml-20 mb-6 font-bold text-gray-700 text-3xl">Gebruikers</h1>

                <?php if (isset($_SESSION['error'])) { ?>
                    <p class="font-bold text-xl p-3 rounded-md bg-red-300 text-red-600 w-fit"><?= $_SESSION['error'] ?></p>
                    <?php unset($_SESSION['error']); ?>
                <?php } ?>

                <table class="w-full">
                    <tr class="*:pb-4">
                        <th>(School)naam</th>
                        <th>Emailadres</th>
                        <th>Datum aangemeld</th>
                        <th>Beheerder</th>
                    </tr>
                    <?php if ($users) : ?>
                        <?php foreach ($users as $user) : ?>
                            <tr class="*:p-3 *:border-t-1 *:border-slate-300 *:text-center hover:bg-slate-50 transition">
                                <td><?= ucfirst($user['naam']) ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= explode(' ', $user['date_created'])[0] ?></td>
                                <td>
                                    <select name="update-user-<?= $user['id'] ?>" id="update-user-<?= $user['id'] ?>"
                                        class="bg-white rounded-md p-2 border-2 border-gray-200">
                                        <?php foreach ($isAdminOptions as $option => $label) : ?>
                                            <option value="<?= $option ?>" <?= ($user['is_admin'] === $option) ? 'selected' : '' ?>>
                                                <?= $label ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </table>
            </div>
            <div class="flex justify-end">
                <button type="submit"
                    class="justify-self-center bg-sky-600 hover:bg-sky-500 cursor-pointer p-2 rounded-lg shadow-md text-white transition">
                    Wijzigingen opslaan
                </button>
            </div>
        </div>
    </form>
</body>

</html>