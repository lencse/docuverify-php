<?php

namespace Lencse\Docuverify;

final class StringCollection implements \Iterator
{
    /**
     * @var string[]
     */
    private $items = [];

    public function current(): string
    {
        return current($this->items);
    }
    
    /**
     * @return false|string
     */
    public function next()
    {
        return next($this->items);
    }
    
    public function key()
    {
        return key($this->items);
    }
    
    public function valid(): bool
    {
        return null !== key($this->items) && false !== key($this->items);
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