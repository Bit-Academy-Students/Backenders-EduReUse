<?php

use Database\Database;

if (!isset($_SESSION['id'])) {
    header('location: /login');
    exit();
}

$db = new Database();
$conn = $db->connect();
$conn->query("USE " . $db->getDbName());

// product types
$sql = "SELECT * FROM `types`";
$stmt = $conn->prepare($sql);
$stmt->execute();
$types = $stmt->fetchAll(PDO::FETCH_ASSOC);

try {
    if (isset($_POST['submit'])) {
        if (! is_csrf_valid()) {
            exit();
        }

        $omschrijving = (!empty($_POST['omschrijving'])) ? htmlspecialchars($_POST['omschrijving']) : throw new Exception('Geen omschrijving meegegeven');
        $type = (!empty($_POST['type'])) ? htmlspecialchars($_POST['type']) : throw new Exception('Geen product type meegegeven');
        $hoeveelheid = (!empty($_POST['hoeveelheid'])) ? htmlspecialchars($_POST['hoeveelheid']) : throw new Exception('Geen hoeveelheid meegegeven');
        if ($hoeveelheid < 0) {
            throw new Exception("Hoeveelheid moet hoger dan 0 zijn.");
        }

        $postcode = (!empty($_POST['postcode'])) ? htmlspecialchars(strtoupper($_POST['postcode'])) : throw new Exception('Geen postcode meegegeven');
        $deadline = (!empty($_POST['deadline'])) ? htmlspecialchars($_POST['deadline']) : null;

        // regex voor postcode
        $pattern = '/^(\d{4})\s?([a-zA-Z]{2})$/';
        if (!preg_match($pattern, $postcode) || strlen($postcode) < 6 || strlen($postcode) > 7) {
            throw new Exception("Verkeerde postcode '$postcode' ingevoerd, houdt het format '1234 AB' aan");
        }

        // reformat postcode
        $replacement = '$1 $2';
        $postcode = preg_replace($pattern, $replacement, $postcode);

        $sql = "INSERT INTO needs (titel, type_id, hoeveelheid, postcode, deadline, user_id, date_created, date_modified, is_completed)
            VALUES (:titel, :typeId, :hoeveelheid, :postcode, :deadline, :userId, :dateCreated, :dateModified, :isCompleted)";

        $exec = $conn->prepare($sql);
        $exec->execute([
            'titel' => $omschrijving,
            'typeId' => $type,
            'hoeveelheid' => $hoeveelheid,
            'postcode' => $postcode,
            'deadline' => $deadline,
            'userId' => $_SESSION['id'],
            'dateCreated' => date('Y-m-d G:i:s'),
            'dateModified' => date('Y-m-d G:i:s'),
            'isCompleted' => 0,
        ]);

        header('location: /user/posts');
        exit();
    }
} catch (Exception $e) {
    $error = $e->getMessage();
} catch (PDOException $ex) {
    $error = $ex->getMessage();
}

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aanvraag</title>

    <link rel="stylesheet" href="src/output.css">
    <?php require_once __DIR__ . '/../components/fontawesome-link.php' ?>
</head>

<body class="bg-gray-100">
    <?php require_once __DIR__ . '/../components/header.php' ?>

    <div class="flex flex-col bg-white justify-self-center shadow-lg w-[40%] gap-3 rounded-lg p-6 my-15">
        <div>
            <h1 class="font-bold text-3xl text-center">Aanvraag Formulier</h1>
        </div>

        <div>
            <form method="post" class="space-y-6">
                <?php set_csrf(); ?>
                <div class="flex items-baseline gap-2">
                    <label for="omschrijving" class="cursor-pointer text-lg">
                        Omschrijving
                    </label>
                    <input type="text"
                        name="omschrijving" id="omschrijving"
                        required
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
                    <label for="hoeveelheid" class="cursor-pointer text-lg">
                        Hoeveelheid
                    </label>
                    <input type="number"
                        name="hoeveelheid" id="hoeveelheid"
                        required
                        value="1" min="0"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="postcode" class="cursor-pointer text-lg">
                        Postcode
                    </label>
                    <input type="text"
                        name="postcode" id="postcode"
                        required
                        placeholder="1234 AB"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                </div>

                <div class="flex items-baseline gap-2">
                    <label for="deadline" class="cursor-pointer text-lg">
                        Deadline

                    </label>
                    <input type="date"
                        id="deadline" name="deadline"
                        class="bg-slate-100 mt-2 rounded-md shadow-xs block w-full rounded-md py-1.5 px-3">
                    <span class="text-gray-500">(optioneel)</span>
                </div>

                <input type="submit"
                    name="submit" value="Vraag aan"
                    class="flex w-full justify-center rounded-md bg-sky-600 px-3 py-1.5 text-sm/6 font-semibold text-white cursor-pointer hover:bg-sky-500 transition">
            </form>
        </div>
        <?php if (isset($error)) { ?>
            <p class="font-bold text-center rounded-md bg-red-300 text-red-600"><?= $error ?></p>
        <?php } ?>
    </div>

    <script>
        const input = document.getElementById('omschrijving');
        input.focus();
        input.select();
    </script>
</body>

</html>