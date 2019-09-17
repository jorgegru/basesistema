<?php

namespace Tests;

use Tests\Functional\BaseTestCase;

class dependenciesTest extends BaseTestCase
{

    /**
     * Test banco de teste no phpunit.xml
     *
     * @return void
     */
    public function testConn()
    {
        $conn = $this->app()->getContainer()['conn'];

        $this->assertInstanceOf('PDO', $conn);
    }

    /**
     * Test banco de teste arquivo configuracao
     *
     * @return void
     */
    public function testConnectionDb()
    {
        $settings = $this->app()->getContainer()['settings']['db'];
        //database connection
        try {
            //mysql pdo connection
                if (strlen($settings['host']) == 0 && strlen($settings['port']) == 0) {
                    //if both host and port are empty use the unix socket
                    $db = new \PDO("mysql:host={$settings['host']};unix_socket=/var/run/mysqld/mysqld.sock;dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                } else {
                    if (strlen($settings['port']) == 0) {
                        //leave out port if it is empty
                        $db = new \PDO("mysql:host={$settings['host']};dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                    }
                    else {
                        $db = new \PDO("mysql:host={$settings['host']};port={$settings['port']};dbname={$settings['dbname']};charset=utf8;", $settings['user'], $settings['pass']);
                    }
                }
                $this->assertInstanceOf('PDO', $db);
        }
        catch (\PDOException $error) {

            $this->assertInstanceOf('PDOException', $error);
            echo "Falha na conexão com o banco de dados";die;
        }
        
    }

    
}