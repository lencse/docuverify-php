<?php declare(strict_types=1);

namespace Test\Mock;

use Lencse\Docuverify\Runner;

final class MockRunner implements Runner
{
    /** @var string[] */
    private $ran = [];

    public function runFile(string $path): void
    {
        $this->ran[] = $path;
    }

    /**
     * @return string[]
     */
    public function ran(): array
    {
        return $this->ran;
    }
}
