<?php

namespace Tests\Functional;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use PHPUnit\Framework\TestCase;


/**
 * This is an example class that shows how you could set up a method that
 * runs the application. Note that it doesn't cover all use-cases and is
 * tuned to the specifics of this skeleton app, so if your needs are
 * different, you'll need to change it.
 */
class BaseTestCase extends TestCase
{
    /**
     * Use middleware when running application?
     *
     * @var bool
     */
    protected $withMiddleware = true;

    // só instancia o pdo uma vez para limpeza de teste e carregamento de ambiente
    static private $pdo = null;

    // só instancia PHPUnit_Extensions_Database_DB_IDatabaseConnection uma vez por teste
    private $conn = null;

    /**
     * Process the application given a request method and URI
     *
     * @param string $requestMethod the request method (e.g. GET, POST, etc.)
     * @param string $requestUri the request URI
     * @param array|object|null $requestData the request data
     * @return \Slim\Http\Response
     */
    public function runApp($requestMethod, $requestUri, $requestData = null)
    {
        // Create a mock environment for testing with
        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        // Set up a request object based on the environment
        $request = Request::createFromEnvironment($environment);

        // Add request data, if it exists
        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        // Set up a response object
        $response = new Response();

        // Use the application settings
        $settings = require __DIR__ . '/../../src/settings.php';

        // Instantiate the application
        $app = new App($settings);

        require __DIR__ . '/../../src/functions.php';

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../src/dependencies.php';
        $dependencies($app);

        // Register middleware
        if ($this->withMiddleware) {
            $middleware = require __DIR__ . '/../../src/middleware.php';
            $middleware($app);
        }

        // Register routes
        $routes = require __DIR__ . '/../../src/routes.php';
        $routes($app);

        // Process the application
        $response = $app->process($request, $response);

        // Return the response
        return $response;
    }


    public function app()
    {

        // Use the application settings
        $settings = require __DIR__ . '/../../src/settings.php';

        // Instantiate the application
        $app = new App($settings);

        require __DIR__ . '/../../src/functions.php';

        // Set up dependencies
        $dependencies = require __DIR__ . '/../../src/dependencies.php';
        $dependencies($app);

        // Register middleware
        if ($this->withMiddleware) {
            $middleware = require __DIR__ . '/../../src/middleware.php';
            $middleware($app);
        }

        // Register routes
        $routes = require __DIR__ . '/../../src/routes.php';
        $routes($app);

        $app->getContainer()['conn'] = $this->getConnection();

     
        return $app;

    }


    

    final public function getConnection()
    {
        if ($this->conn === null) {
            if ($this->conn == null) {
                $this->conn = new \PDO( $GLOBALS['DB_DSN'], $GLOBALS['DB_USER'], $GLOBALS['DB_PASSWD'] );
            }
            //$this->conn = $this->createDefaultDBConnection(self::$pdo, $GLOBALS['DB_DBNAME']);
        }

        return $this->conn;
    }
}
