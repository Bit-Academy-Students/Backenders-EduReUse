<?php

use Database\Database;

$db = new Database('edureuse');
$conn = $db->connect();
$conn->query("USE edureuse");

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link rel="stylesheet" href="resources/style.css">
</head>

<body class="home-body">
    <?php require_once 'components/header.php' ?>

    <div class="banner">
        <div class="wrapper">
            <h2>Lorem ipsum dolor sit amet consectetur adipisicing elit.</h2>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, nostrum. Nemo ipsa omnis
                illum vitae, laboriosam id iusto odio. Consequatur dolores quae culpa sequi earum officiis eveniet
                ullam dolor accusantium?</p>

            <a href="/register">Meld je nu aan</a>
        </div>
    </div>

</body>

</html>