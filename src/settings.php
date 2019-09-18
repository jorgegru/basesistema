<?php

require_once __DIR__.'/../vendor/autoload.php';
if (file_exists(base_path().'/.env')) {
    $dotenv = Dotenv\Dotenv::create(base_path());
    $dotenv->load();
} else {
    echo 'Arquivo de configuração não encontrado';
    die;
}

return [
    'settings' => [
        'displayErrorDetails'    => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__.'/../src/App/Views/',
        ],

        // Monolog settings
        'logger' => [
            'name'  => 'slim-app',
            'path'  => isset($_ENV['docker']) ? 'php://stdout' : __DIR__.'/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],

        // Database connection settings
        'db' => [
            'driver' => env('DB_DRIVER', 'mysql'),
            'host'   => env('DB_HOST', 'localhost'),
            'port'   => env('DB_PORT', ''),
            'dbname' => env('DB_NAME', 'indique'),
            'user'   => env('DB_USER', 'root'),
            'pass'   => env('DB_PASS', ''),
        ],
    ],
];
