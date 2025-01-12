<?php
/*
|--------------------------------------------------------------------------
| App routes
|--------------------------------------------------------------------------
*/
$routes = [
    'login' => ['login','logout','forgot','reset'],
    'fault' => ['error'],
    'home' => ['default'],
    'passbook' => ['default', 'add', 'category', 'payment'],
    'user' => ['search','sheet'],
    'advanced' => ['options', 'icons_list', 'export'],
    'weight' => ['default'],
];