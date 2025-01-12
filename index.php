<?php
session_name('mybook');
session_start();
date_default_timezone_set('Asia/Kolkata');

$app = require_once 'core/app.php';
$app->init();