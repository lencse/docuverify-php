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
    private $items = [];

    public function current(): string
    {
        return current($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function key(): int
    {
        return (int) key($this->items);
    }

    public function valid(): bool
    {
        return ! empty(key($this->items));
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function push(string $item): self
    {
        $result = new self();
        $result->items = $this->items;
        $result->items[] = $item;

        return $result;
    }
}
