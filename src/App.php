<?php declare(strict_types=1);

namespace Lencse\Docuverify;

class App
{
    /** @var Configurator */
    private $configurator;

    /** @var Tester */
    private $tester;

    public function __construct(Configurator $configurator, Tester $tester)
    {
        $this->configurator = $configurator;
        $this->tester = $tester;
    }

    public function run(string $configFilePath): void
    {
        $config = $this->configurator->fromXml($configFilePath);
        $this->tester->verify($config);
    }
}
