<?php declare(strict_types=1);

namespace Lencse\Docuverify;

final class Configuration
{
    /** @var StringCollection */
    private $files;

    /** @var string */
    private $bootstrapPath;

    /** @var string */
    private $header;

    /** @var string */
    private $currentDir;

    public function __construct(string $bootstrapPath, string $header, string $currentDir)
    {
        $this->files = new StringCollection();
        $this->bootstrapPath = $bootstrapPath;
        $this->header = $header;
        $this->currentDir = $currentDir;
    }

    public function files(): StringCollection
    {
        return $this->files;
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

    public function withFile(string $fileName): self
    {
        $result = $this->copy();
        $result->files = $result->files()->push($fileName);

        return $result;
    }

    private function copy(): self
    {
        $result = new self($this->bootstrapPath(), $this->header(), $this->currentDir());
        $result->files = $this->files();

        return $result;
    }
}
