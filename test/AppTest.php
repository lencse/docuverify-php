<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\App;
use Lencse\Docuverify\Configurator;
use Lencse\Docuverify\Tester;
use PHPUnit\Framework\TestCase;
use Test\Mock\MockRunner;
use function sys_get_temp_dir;
use function uniqid;

class AppTest extends TestCase
{
    public function testFromXml(): void
    {
        $tmpDir = sys_get_temp_dir() . '/' . uniqid('', true);
        $runner = new MockRunner();
        $app = new App(
            new Configurator(),
            new Tester(
                $runner,
                $tmpDir
            )
        );
        $app->run(__DIR__ . '/fixtures/dir01/docuverify.xml');
        $this->assertTrue($runner->failed());
    }
}
