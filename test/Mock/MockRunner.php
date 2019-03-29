<?php declare(strict_types=1);

namespace Test\Mock;

use Lencse\Docuverify\Runner;
use Symfony\Component\Finder\SplFileInfo;
use function strpos;

final class MockRunner implements Runner
{
    /** @var string[] */
    private $ran = [];

    /** @var bool */
    private $failed = false;

    public function runFile(string $path): void
    {
        $file = new SplFileInfo($path, '', '');
        $content = $file->getContents();
        $this->ran[] = $path;
        if (strpos($content, 'ERROR') === false) {
            return;
        }
        $this->failed = true;
    }

    /**
     * @return string[]
     */
    public function ran(): array
    {
        return $this->ran;
    }

    public function failed(): bool
    {
        return $this->failed;
    }
}
