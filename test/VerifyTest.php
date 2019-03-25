<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\Configuration;
use Lencse\Docuverify\Tester;
use PHPUnit\Framework\TestCase;

class VerifyTest extends TestCase
{
    public function testTester(): void
    {
        $config = new Configuration('bootstrap', 'header');
        $this->assertEquals('bootstrap', $config->bootstrapPath());
        $tester = new Tester();
        $this->assertNotNull($tester);
    }
}
