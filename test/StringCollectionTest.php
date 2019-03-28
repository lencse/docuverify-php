<?php declare(strict_types=1);

namespace Test;

use Lencse\Docuverify\StringCollection;
use PHPUnit\Framework\TestCase;
use function iterator_to_array;

class StringCollectionTest extends TestCase
{
    public function testCollection(): void
    {
        $strings = new StringCollection();
        $this->assertEquals(['test'], iterator_to_array($strings->push('test')));
        $this->assertEquals([], iterator_to_array($strings));
    }
}
