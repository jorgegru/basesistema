<?php

namespace Tests;

use Tests\Functional\BaseTestCase;

class functionsTest extends BaseTestCase
{
    /**
     * Test validando email.
     */
    public function testEmailValidate()
    {
        $this->assertTrue(email_validate('jorgegru@gmail.com'));
        $this->assertFalse(email_validate('jorgegru'));
        $this->assertFalse(email_validate('jorgegru@gmail'));
    }
}
