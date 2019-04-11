<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use function shell_exec;

class RealRunner implements Runner
{
    public function runFile(string $path): void
    {
        shell_exec('php ' . $path);
    }
}
