<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$id = $_SESSION['id'];

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// product states
$sql = "SELECT * FROM `product_states`";
$states = $conn->query($sql);

// product types
$sql = "SELECT * FROM `types`";
$types = $conn->query($sql);

$sql = "SELECT * FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$recordset = $stmt->execute(['id' => $id]);

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doneer</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>
    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[40%] gap-3 rounded-lg p-6 my-15">
        <div>
            <h1 class="font-bold text-3xl text-center">Donatie Formulier</h1>
        </div>
        <div id="content">
            <form method="post" enctype="multipart/form-data" class="space-y-6">
                <div class="flex items-baseline gap-2">
                    <label for="titel" class="cursor-pointer text-lg">Titel:</label>
                    <input type="text" name="titel" id="titel" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="type" class="cursor-pointer text-lg">Type:</label>
                    <select name="type" id="type" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                        <?php foreach ($types as $type) { ?>
                            <option value="<?= $type['id'] ?>"><?= $type['type'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="aantal" class="cursor-pointer text-lg">Aantal:</label>
                    <input type="number" name="aantal" id="aantal" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="beschrijving" class="cursor-pointer text-lg">Beschrijving:</label>
                    <textarea class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3" name="beschrijving" id="beschrijving"></textarea>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="staat" class="cursor-pointer text-lg">Staat:</label>
                    <select name="staat" id="staat" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                        <?php foreach ($states as $state) { ?>
                            <option value="<?php echo $state['id'] ?>"><?php echo $state['label'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="product_url" class="cursor-pointer text-lg">Link naar orginele product:</label>
                    <input type="text" name="product_url" id="product_url" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="postcode" class="text-lg">Postcode:</label>
                    <input type="text" name="postcode" id="postcode" class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2 text-lg">
                    <label for="image" class="text-lg cursor-pointer flex w-full justify-center rounded-md bg-slate-100 px-3 py-1.5 font-semibold text-black">Voeg foto toe</label>
                    <input type="file" name="image" id="image">
                </div>
                <input class="cursor-pointer flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white hover:bg-sky-500 transition" type="submit" name="submit" value="Doneer">
            </form>

            <?php if (isset($_SESSION['error'])) { ?>
                <p class="font-bold text-center rounded-md bg-red-300 text-red-600"><?= $_SESSION['error'] ?></p>
                <?php unset($_SESSION['error']) ?>
            <?php } ?>
        </div>
    </div>

    <script>
        const input = document.getElementById('titel');
        input.focus();
        input.select();
    </script>
</body>

</html>