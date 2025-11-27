<?php

use Controllers\DonateController;
use Controllers\NeedController;

require_once __DIR__ . '/router.php';

// homepage
get('/', 'views/homepage.php');

// donaties
get('/doneer', 'views/doneer.php');
post('/doneer', function () {
    try {
        // voeg donatie toe aan database
        new DonateController()->post();
    } catch (Exception $e) {
        $_SESSION['error'] =  $e->getMessage();
        header('location: /doneer');
        exit();
    }
});

// aanvragen
get('/aanvraag', 'views/aanvraag.php');
post('/aanvraag', function () {
    try {
        new NeedController()->post();
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        header('location: /aanvraag');
        exit();
    }
});

// admin pagina's
get('/admin', 'views/admin/adminList.php');
get('/admin/aanbiedingen', 'views/admin/offers.php');
get('/admin/aanvragen', 'views/admin/needs.php');
get('/admin/matches', 'views/admin/matches.php');

// TODO: verwijder hieronder
get('/admin/list', 'views/admin/allRows.php');


// login
get('/login', 'views/login.php');
post('/login', 'views/login.php');

// register
get('/register', 'views/register.php');
post('/register', 'views/register.php');

// logout
get('/logout', 'views/logout.php');

// 
get('/school-posts', 'views/schoolPosts.php');




// error pagina's
any('/404', 'views/404.php');
