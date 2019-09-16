<?php

namespace Tests;

use Tests\Functional\BaseTestCase;

class dependenciesTest extends BaseTestCase
{

    public function testConn()
    {
        $conn = $this->app()->getContainer()['conn'];

        $this->assertInstanceOf('PDO', $conn);
    }

    
}