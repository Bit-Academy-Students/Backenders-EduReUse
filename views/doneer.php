<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// product states
$sql = "SELECT * FROM `product_states`";
$states = $conn->query($sql);

// product types
$sql = "SELECT * FROM `types`";
$types = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doneer</title>

    <link rel="stylesheet" href="src/output.css">
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/components/header.php' ?>

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[40%] gap-3 rounded-lg p-6 my-7">
        <div>
            <h1 class="font-bold text-3xl text-center">Donatie Formulier</h1>
        </div>

        <div>
            <form method="post" class="space-y-3">
                <div class="flex items-baseline gap-2">
                    <label for="titel" class="cursor-pointer text-lg">
                        Titel
                    </label>
                    <input type="text"
                        name="titel" id="titel"
                        placeholder="Bijvoorbeeld: 'een mooie nieuwe laptop'"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="type" class="cursor-pointer text-lg">
                        Type
                    </label>
                    <select name="type"
                        id="type"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                        <?php foreach ($types as $type) { ?>
                            <option value="<?= $type['id'] ?>"><?= $type['type'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="aantal" class="cursor-pointer text-lg">
                        Aantal
                    </label>
                    <input type="number"
                        name="aantal" id="aantal"
                        value="1"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="beschrijving" class="cursor-pointer text-lg">
                        Beschrijving
                    </label>
                    <textarea name="beschrijving" id="beschrijving"
                        placeholder="Bijvoorbeeld: 'Een laptop in goede staat'"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block h-9 w-full rounded-md py-1.5 px-3"></textarea>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="staat" class="cursor-pointer text-lg">
                        Staat
                    </label>
                    <select name="staat"
                        id="staat"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                        <?php foreach ($states as $state) { ?>
                            <option value="<?= $state['id'] ?>"><?= $state['label'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="postcode" class="cursor-pointer text-lg">
                        Ophaalpostcode
                    </label>
                    <input type="text"
                        name="postcode" id="postcode"
                        placeholder="1234 AB"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="">
                    <label for="url" class="cursor-pointer text-lg">
                        Url naar product:
                    </label>
                    <span class="text-gray-500">(optioneel)</span>
                    <input type="text"
                        name="url" id="url"
                        placeholder="Bijvoorbeeld: 'bol.com/productnaam'"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <input type="submit"
                    name="submit" value="Doneer"
                    class="flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white cursor-pointer hover:bg-sky-500 transition">
            </form>
        </div>
        <?php if (isset($_SESSION['error'])) { ?>
            <p class="font-bold text-center rounded-md bg-red-300 text-red-600"><?= $_SESSION['error'] ?></p>
            <?php unset($_SESSION['error']) ?>
        <?php } ?>
    </div>
</body>

</html>