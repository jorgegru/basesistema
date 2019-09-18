<?php

$config = require __DIR__."/src/settings.php";
return [

    // Database settings
    'PHPMigrate'    => [
        'driver'    => $config['settings']['db']['driver'],
        'host'      => $config['settings']['db']['host'],
        'database'  => $config['settings']['db']['dbname'],
        'username'  => $config['settings']['db']['user'],
        'password'  => $config['settings']['db']['pass'],
        'charset'   => 'utf8',
        'path'      => __DIR__.'/migrations/', 
    ],
];