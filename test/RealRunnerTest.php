<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\RealRunner;
use PHPUnit\Framework\TestCase;

class RealRunnerTest extends TestCase
{
    public function testRunFile(): void
    {
        $runner = new RealRunner();
        $runner->runFile(__DIR__ . '/fixtures/code/file1.php');
        $this->assertTrue(true);
    }
}
