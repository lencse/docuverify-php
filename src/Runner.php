<?php declare(strict_types=1);

namespace Lencse\Docuverify;

interface Runner
{
    public function runFile(string $path): void;
}
