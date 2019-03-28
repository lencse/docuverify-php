<?php declare(strict_types=1);

namespace Lencse\Docuverify;

use Iterator;
use function current;
use function key;
use function next;
use function reset;

final class StringCollection implements Iterator
{
    /** @var string[] */
    private $strings = [];

    public function current(): string
    {
        return current($this->strings);
    }

    /**
     * @return false|string
     */
    public function next()
    {
        return next($this->strings);
    }

    /**
     * @return mixed
     */
    public function key()
    {
        return key($this->strings);
    }

    public function valid(): bool
    {
        return key($this->strings) !== null && key($this->strings) !== false;
    }

    public function rewind(): void
    {
        reset($this->strings);
    }

    public function push(string $str): self
    {
        $result = new self();
        $result->strings = $this->strings;
        $result->strings[] = $str;

        return $result;
    }
}
