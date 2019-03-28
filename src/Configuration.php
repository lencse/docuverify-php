<?php declare(strict_types=1);

namespace Lencse\Docuverify;

final class Configuration
{
    /** @var StringCollection */
    private $patterns;

    /** @var StringCollection */
    private $excludedPatterns;

    /** @var string */
    private $bootstrapPath;

    /** @var string */
    private $header;

    /** @var string */
    private $currentDir;

    public function __construct(string $bootstrapPath, string $header, string $currentDir)
    {
        $this->excludedPatterns = new StringCollection();
        $this->patterns = new StringCollection();
        $this->bootstrapPath = $bootstrapPath;
        $this->header = $header;
        $this->currentDir = $currentDir;
    }

    public function patterns(): StringCollection
    {
        return $this->patterns;
    }

    public function excludedPatterns(): StringCollection
    {
        return $this->excludedPatterns;
    }

    public function bootstrapPath(): string
    {
        return $this->bootstrapPath;
    }

    public function header(): string
    {
        return $this->header;
    }

    public function currentDir(): string
    {
        return $this->currentDir;
    }

    public function withPattern(string $pattern): self
    {
        $result = $this->copy();
        $result->patterns = $result->patterns()->push($pattern);

        return $result;
    }

    public function withExcludedPattern(string $pattern): self
    {
        $result = $this->copy();
        $result->excludedPatterns = $result->excludedPatterns()->push($pattern);

        return $result;
    }

    private function copy(): self
    {
        $result = new self($this->bootstrapPath(), $this->header(), $this->currentDir());
        $result->patterns = $this->patterns();
        $result->excludedPatterns = $this->excludedPatterns();

        return $result;
    }
}
