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
        $this->runner = new MockRunner();
        $this->tester = new Tester($this->runner);
        $this->tmpDir = sys_get_temp_dir() . '/' . uniqid('', true);
    }

    public function testEmptyConfig(): void
    {
        $config = $this->config();
        $this->tester->run($config, $this->tmpDir);
        $this->assertEquals([], $this->runner->ran());
    }

    public function testWithFileWithoutCode(): void
    {
        $config = $this->config()->withPattern('01.md');
        $this->tester->run($config, $this->tmpDir);
        $this->assertEquals([], $this->runner->ran());
    }

    public function testWithFileWithOneCode(): void
    {
        $config = $this->config()->withPattern('02.md');
        $this->tester->run($config, $this->tmpDir);
        $this->assertEquals([$this->tmpDir . '/file0.php'], $this->runner->ran());
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
