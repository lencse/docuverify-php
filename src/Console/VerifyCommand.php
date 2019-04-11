<?php declare(strict_types=1);

namespace Lencse\Docuverify\Console;

use Lencse\Docuverify\App;
use Lencse\Docuverify\Configurator;
use Lencse\Docuverify\RealRunner;
use Lencse\Docuverify\Tester;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function getcwd;
use function implode;
use function sys_get_temp_dir;
use function uniqid;

class VerifyCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'verify';

    protected function configure(): void
    {
        $this->setDescription('Verify');
        $this->addOption(
            'configFile',
            'c',
            InputOption::VALUE_OPTIONAL,
            'The docuverify config XML',
            'docuverify.xml'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $app = new App(
            new Configurator(),
            new Tester(
                new RealRunner(),
                sys_get_temp_dir() . '/' . uniqid('', true)
            )
        );
        $configFile = implode('/', [
            getcwd(),
            $input->getOption('configFile'),
        ]);
        $app->run($configFile);
        $output->writeln('OK');

        return 0;
    }
}
