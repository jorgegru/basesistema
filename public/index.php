<?php
if (PHP_SAPI == 'cli-server') {
    $url = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__.$url['path'];
    if (is_file($file)) {
        return false;
    }
}

require __DIR__.'/../vendor/autoload.php';

//start the session
ini_set('session.use_only_cookies', true);
ini_set('session.cookie_httponly', true);
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { 
    ini_set('session.cookie_secure', true); 
}
if (!isset($_SESSION)) { 
    session_start(); 
}

if (file_exists(base_path().'/.env')) {
    $dotenv = Dotenv\Dotenv::create(base_path());
    $dotenv->load();
} else {
    echo 'Arquivo de configuração não encontrado';die;
}

// Instantiate the app
$settings = require base_path().'/src/settings.php';
$app = new \Slim\App($settings);

// Set up dependencies
$dependencies = require base_path().'/src/dependencies.php';
$dependencies($app);

// Register middleware
$middleware = require base_path().'/src/middleware.php';
$middleware($app);

// Register routes
$routes = require base_path().'/src/routes.php';
$routes($app);

// Run app
$app->run();
