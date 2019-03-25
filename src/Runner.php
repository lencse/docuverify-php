<?php

namespace Lencse\Docuverify;

interface Runner
{
    public function runFile(string $path): void ;
}
