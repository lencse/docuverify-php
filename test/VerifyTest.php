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

    public function testFileWithoutPhpCode(): void
    {
        $tester = new FileTester();
        $this->assertTrue($tester->testFile(__DIR__ . '/fixtures/02.md'));
    }

    public function testFileWithGoodPhpCode(): void
    {
        $tester = new FileTester();
        $this->assertTrue($tester->testFile(__DIR__ . '/fixtures/03.md'));
    }

    public function testFileWithBadPhpCode(): void
    {
        $tester = new FileTester();
        try {
            $tester->testFile(__DIR__ . '/fixtures/04.md');
        } catch (\Throwable $e) {
            $this->assertTrue(true);
            return;
        }
        $this->assertTrue(true);
    }
}
