<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\Configurator;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;
use function strpos;

class ConfiguratorTest extends TestCase
{
    public function testFromXml(): void
    {
        $configurator = new Configurator();
        $config = $configurator->fromXml(__DIR__ . '/fixtures/xml/default.xml');
        $this->assertEquals('vendor/autoload.php', $config->bootstrapPath());
        $this->assertEquals(
            "<?php\n\nnamespace Readme;\n\nrequire_once '%%AUTOLOAD_PATH%%';\n",
            $config->header()
        );
        $this->assertEquals(__DIR__ . '/fixtures/xml', $config->currentDir());
        $this->assertEquals([
            'README.md',
            'doc/INSTALLATION.md',
        ], iterator_to_array($config->files()));
    }

    public function testWrongXml(): void
    {
        $configurator = new Configurator();
        $this->expectException(Error::class);
        $configurator->fromXml(__DIR__ . '/fixtures/xml/wrong.xml');
        $this->assertTrue(strpos($this->getExpectedException()->getMessage(), 'header') !== false);
    }
}
