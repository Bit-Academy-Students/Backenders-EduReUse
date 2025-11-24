<?php

$donatie = null;
if (isset($_POST['submit'])) {
    $donatie = [
        'type' => $_POST['type'],
        'aantal' => $_POST['aantal'],
        'staat' => $_POST['staat'],
        'postcode' => $_POST['postcode']
    ];

    $donatie = [
        'type' => $_POST['type'],
        'aantal' => $_POST['aantal'],
        'staat' => $_POST['staat'],
        'postcode' => $_POST['postcode']
    ];
}
?>

<html>

<head>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <div class="container">
        <div id="header">
            <h1>Donatie Formulier</h1>
        </div>
        <div id="content">
            <form action="formulier.php" method="post">
                Type:<br />
                <select name="type">
                    <option value="Laptop ">Laptop</option>
                    <option value="Robots ">Robots</option>
                    <option value="3D-printers ">3D-printers</option>
                </select><br /><br />
                Aantal:<br /> <input type="number" name="aantal"><br /><br />
                Staat:<br />
                <select name="staat">
                    <option value="Nieuw">Nieuw</option>
                    <option value="Gebruikt">Gebruikt</option>
                </select><br /><br />
                Postcode:<br /> <input type="text" name="postcode"><br /><br />
                <input type="submit" name="submit" value="Doneer">
            </form>

            <?php if ($donatie): ?>
                <div class="donatie-card">Bedankt voor uw donatie!</div>
                <h2>Donatie Details</h2>
                <div class="donatie-info"></div>

                <ul>
                    <li>Type: <?php echo htmlspecialchars($donatie['type']); ?></li>
                    <li>Aantal: <?php echo htmlspecialchars($donatie['aantal']); ?></li>
                    <li>Staat: <?php echo htmlspecialchars($donatie['staat']); ?></li>
                    <li>Postcode: <?php echo htmlspecialchars($donatie['postcode']); ?></li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>