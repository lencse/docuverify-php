<?php

namespace Test;

use Lencse\Docuverify\FileTester;
use PHPUnit\Framework\TestCase;

class VerifyTest extends TestCase
{
    public function testFileWithoutCode(): void
    {
        $tester = new FileTester();
        $this->assertTrue($tester->testFile(__DIR__ . '/fixtures/01.md'));
    }
}
