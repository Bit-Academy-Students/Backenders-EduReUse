<?php

require_once __DIR__ . '/router.php';

get('/', 'views/home.php');

get('/doneer', 'views/doneer.php');

get('/aanvraag', 'views/aanvraag.php');

get('/admin', 'views/admin/adminPage.php');

any('/404', 'views/404.php');
