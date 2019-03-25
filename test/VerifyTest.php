<?php

namespace Test;

use Lencse\Docuverify\Tester;
use PHPUnit\Framework\TestCase;

class VerifyTest extends TestCase
{
    public function testTester(): void
    {
        $tester = new Tester();
        $this->assertNotNull($tester);
    }
}
