<?php declare(strict_types=1);

namespace Lencse\Docuverify;

class Configuration
{
    /** @var StringCollection */
    private $patterns;

    /** @var StringCollection */
    private $excludedPatterns;

    /** @var string */
    private $bootstrapPath;

    /** @var string */
    private $header;

    public function __construct(string $bootstrapPath, string $header)
    {
        $this->excludedPatterns = new StringCollection();
        $this->patterns = new StringCollection();
        $this->bootstrapPath = $bootstrapPath;
        $this->header = $header;
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
}
