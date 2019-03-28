<?php declare(strict_types=1);

namespace Test\Mock;

use Lencse\Docuverify\Runner;
use Symfony\Component\Finder\SplFileInfo;
use function strpos;

final class MockRunner implements Runner
{
    /** @var string[] */
    private $ran = [];

    public function runFile(string $path): bool
    {
        $file = new SplFileInfo($path, '', '');
        $content = $file->getContents();
        if (strpos($content, 'ERROR') !== false) {
            return false;
        }
        $this->ran[] = $path;

        return true;
    }

    /**
     * @return string[]
     */
    public function ran(): array
    {
        return $this->ran;
    }
}
