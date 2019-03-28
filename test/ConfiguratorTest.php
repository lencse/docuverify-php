<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\Configurator;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

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
}
