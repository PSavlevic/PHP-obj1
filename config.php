<?php

define('ROOT', __DIR__);

define('STORAGE_FILE', ROOT . '/data/text.txt');

define('PLAYER_COOKIE', 'player');

$nav = [
        [
        'url' => '/',
        'title' => 'Home'
    ],
        [
        'url' => '/create.php',
        'title' => 'Create'
    ],
        [
        'url' => '/join.php',
        'title' => 'Joint'
    ],
        [
        'url' => '/play.php',
        'title' => 'Play'
    ],
        [
        'url' => '/scoreboard.php',
        'title' => 'Scoreboard'
    ]
];
