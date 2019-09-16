<?php

use Slim\App;

return function (App $app) {
    $container = $app->getContainer();

    // view renderer
    $container['renderer'] = function ($c) {
        $settings = $c->get('settings')['renderer'];
        return new \Slim\Views\PhpRenderer($settings['template_path']);
    };

    // monolog
    $container['logger'] = function ($c) {
        $settings = $c->get('settings')['logger'];
        $logger = new \Monolog\Logger($settings['name']);
        $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
        $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
        return $logger;
    };


    $container['conn'] = function ($c) {
        $settings = $c->get('settings')['db'];

        if ($settings['driver'] == "mysql") {
            //database connection
            try {
                //mysql pdo connection
                    if (strlen($settings['host']) == 0 && strlen($settings['port']) == 0) {
                        //if both host and port are empty use the unix socket
                        $db = new PDO("mysql:host={$settings['host']};unix_socket=/var/run/mysqld/mysqld.sock;dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                    } else {
                        if (strlen($settings['port']) == 0) {
                            //leave out port if it is empty
                            $db = new PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                        }
                        else {
                            $db = new PDO("mysql:host={$settings['host']};port={$settings['port']};dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                        }
                    }
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                    return $db;
            }
            catch (PDOException $error) {
                print "error: " . $error->getMessage() . "<br/>";
                die();
            }
        } 

    };
};
