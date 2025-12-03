<?php

use Controllers\DonateController;
use Controllers\NeedController;

require_once __DIR__ . '/router.php';

// homepage
get('/', 'views/homepage.php');

// login
get('/login', 'views/login.php');
post('/login', 'views/login.php');

// register
get('/register', 'views/register.php');
post('/register', 'views/register.php');

// logout
get('/logout', 'views/logout.php');

// user's posts
get('/school-posts', 'views/schoolPosts.php');

// donaties
get('/doneer', 'views/doneer.php');
post('/doneer', function () {
    try {
        // voeg donatie toe aan database
        $controller = new DonateController();
        $controller->post();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /doneer');
        exit();
    }
});

// aanvragen
get('/aanvraag', 'views/aanvraag.php');
post('/aanvraag', 'views/aanvraag.php');

// admin pagina's
if ($_SERVER['REQUEST_URI'] === '/admin') {
    header('location: /admin/alles');
    exit();
}
get('/admin/alles', 'views/admin/adminList.php');
get('/admin/aanbiedingen', 'views/admin/offers.php');
get('/admin/aanvragen', 'views/admin/needs.php');
get('/admin/matches', 'views/admin/matches.php');

// jenebi aanbod
get('/aanbod', 'views/aanbod.php');

// error pagina's
any('/404', 'views/404.php');
