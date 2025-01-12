<?php
/**
 * CRON: Encrypt user passwords with hash function
 */
require_once __DIR__.'/../config/config.php';
require_once __DIR__.'/../models/model.php';
require_once __DIR__.'/../models/user.php';

$db = new mysqli($config['host'], $config['login'], $config['password'], $config['database']);
$db->set_charset('utf8');

$user = new UserModel($db, $config);

//commented to prevent multiple encryption
$user->encryptPasswords();
