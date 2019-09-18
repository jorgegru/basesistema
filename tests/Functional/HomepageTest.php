<?php

namespace Tests\Functional;

class HomepageTest extends BaseTestCase
{
    /**
     * Test that the index route returns a rendered response containing the text 'SlimFramework' but not a greeting.
     */
    public function testGetHomepageWithoutName()
    {

        // var_dump($this->app()->getContainer()['conn']);die;
        $response = $this->runApp('GET', '/');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Slim2Framework', (string) $response->getBody());
        $this->assertStringNotContainsString('Hello', (string) $response->getBody());
    }

    /**
     * Test that the index route with optional name argument returns a rendered greeting.
     */
    public function testGetHomepageWithGreeting()
    {
        $response = $this->runApp('GET', '/name');

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertStringContainsString('Hello name!', (string) $response->getBody());
    }

    /**
     * Test that the index route won't accept a post request.
     */
    public function testPostHomepageNotAllowed()
    {
        $response = $this->runApp('POST', '/', ['test']);

        $this->assertEquals(405, $response->getStatusCode());
        $this->assertStringContainsString('Method not allowed', (string) $response->getBody());
    }
}
