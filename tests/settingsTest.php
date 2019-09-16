<?php

namespace Tests;

use Tests\Functional\BaseTestCase;

class settingsTest extends BaseTestCase
{
    /**
     * Test validando email
     */
    public function testSettingsDb()
    {
        $settings = $this->app()->getContainer()['settings'];

        $this->assertArrayHasKey('db', $settings);
        $this->assertArrayHasKey('driver', $settings['db']);
        $this->assertArrayHasKey('host', $settings['db']);
        $this->assertArrayHasKey('port', $settings['db']);
        $this->assertArrayHasKey('dbname', $settings['db']);
        $this->assertArrayHasKey('user', $settings['db']);
        $this->assertArrayHasKey('pass', $settings['db']);
    }
}