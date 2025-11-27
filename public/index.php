<?php

require_once __DIR__ . '/../vendor/autoload.php';

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
    <link rel="stylesheet" href="src/output.css">
    </head>
        <body>
            <header class="bg-sky-300 p-3">
                <div >
                    <div class="flex flex-row-end">
                        <a href="index.php">LOGO</a>
                    </div>
                    <nav class="flex flex-row-reverse">
                        <a class="flex bg-white rounded-lg w-30 justify-center items-center" href="login.php">Login</a>
                    </nav>
                </div>
            </header>

            <div class="flex flex-row-end">
                <div class="flex flex-col p-20">
                    <h1 class="text-3xl font-bold">Lorem ipsum dolor sit amet consectetur adipisicing elit.</h1>
                    <p class="mt-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, nostrum. Nemo ipsa omnis 
                        illum vitae, laboriosam id iusto odio. Consequatur dolores quae culpa sequi earum officiis eveniet 
                        ullam dolor accusantium?</p>

                    <a class="bg-sky-500 text-white rounded-md p-1.5 w-30 mt-5" href="register.php">Meld je nu aan</a>
                </div>
                <div class="pt-20 pr-20">
                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusantium, nostrum. Nemo ipsa omnis 
                        illum vitae, laboriosam id iusto odio. Consequatur dolores quae culpa sequi earum officiis eveniet 
                        ullam dolor accusantium?</p>
                </div>
            </div>

        </body>
</html>
