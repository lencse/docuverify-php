<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\Configuration;
use Lencse\Docuverify\Tester;
use PHPUnit\Framework\TestCase;
use Test\Mock\MockRunner;
use function realpath;
use function sys_get_temp_dir;
use function uniqid;

class VerifyTest extends TestCase
{
    /** @var MockRunner */
    private $runner;

    /** @var Tester */
    private $tester;

    /** @var string */
    private $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = sys_get_temp_dir() . '/' . uniqid('', true);
        $this->runner = new MockRunner();
        $this->tester = new Tester($this->runner, $this->tmpDir);
    }

    public function testEmptyConfig(): void
    {
        $config = $this->config();
        $this->tester->verify($config);
        $this->assertEquals([], $this->runner->ran());
    }

    public function testWithFileWithoutCode(): void
    {
        $config = $this->config()->withFile('01.md');
        $this->tester->verify($config);
        $this->assertEquals([], $this->runner->ran());
    }

    public function testWithFileWithOneCode(): void
    {
        $config = $this->config()->withFile('02.md');
        $this->tester->verify($config);
        $this->assertFalse($this->runner->failed());
        $this->assertEquals([$this->tmpDir . '/02.md/snippet1.php'], $this->runner->ran());
    }

    public function testNonExistingFile(): void
    {
        $config = $this->config()->withFile('02.md')->withFile('missing.md');
        $this->tester->verify($config);
        $this->assertFalse($this->runner->failed());
        $this->assertEquals([$this->tmpDir . '/02.md/snippet1.php'], $this->runner->ran());
    }

    public function testWithFileWithWrongCode(): void
    {
        $config = $this->config()->withFile('03.md');
        $this->tester->verify($config);
        $this->assertTrue($this->runner->failed());
        $this->assertEquals([
            $this->tmpDir . '/03.md/snippet1.php',
            $this->tmpDir . '/03.md/snippet2.php',
        ], $this->runner->ran());
    }

    public function testRunningFileNames(): void
    {
        $config = $this->config()->withFile('02.md')->withFile('doc/01.md');
        $this->tester->verify($config);
        $this->assertFalse($this->runner->failed());
        $this->assertEquals([
            $this->tmpDir . '/02.md/snippet1.php',
            $this->tmpDir . '/doc/01.md/snippet1.php',
        ], $this->runner->ran());
    }

    private function config(): Configuration
    {
        $bootstrapPath = 'vendor/autoload.php';
        $header = <<<EOF
<?php

require_once '%%BOOTSTRAP%%';

namespace Test;
EOF;
        $currentDir = realpath(__DIR__ . '/fixtures');

        return new Configuration($bootstrapPath, $header, $currentDir);
    }
}
