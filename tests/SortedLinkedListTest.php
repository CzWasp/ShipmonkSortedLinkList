<?php

namespace Shipmonk;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class SortedLinkedListTest extends TestCase
{
    public function testInsertAndToArrayWithIntegers(): void
    {
        $list = new SortedLinkedList();
        $list->insert(5);
        $list->insert(3);
        $list->insert(7);

        $this->assertSame([3, 5, 7], $list->toArray());
    }

    public function testInsertAndToArrayWithStrings(): void
    {
        $list = new SortedLinkedList();
        $list->insert("pear");
        $list->insert("apple");
        $list->insert("banana");

        $this->assertSame(["apple", "banana", "pear"], $list->toArray());
    }

    public function testMixedTypeThrowsException(): void
    {
        $list = new SortedLinkedList();
        $list->insert(1);

        $this->expectException(InvalidArgumentException::class);
        $list->insert("string");
    }

    public function testContains(): void
    {
        $list = new SortedLinkedList();
        $list->insert(10);
        $list->insert(5);
        $list->insert(20);

        $this->assertTrue($list->contains(5));
        $this->assertFalse($list->contains(100));
    }

    public function testRemove(): void
    {
        $list = new SortedLinkedList();
        $list->insert(2);
        $list->insert(1);
        $list->insert(3);

        $this->assertTrue($list->remove(2));
        $this->assertSame([1, 3], $list->toArray());

        $this->assertFalse($list->remove(100));
    }

    public function testCount(): void
    {
        $list = new SortedLinkedList();
        $this->assertSame(0, $list->count());

        $list->insert(1);
        $list->insert(2);

        $this->assertSame(2, $list->count());

        $list->remove(1);
        $this->assertSame(1, $list->count());
    }

    public function testIterator(): void
    {
        $list = new SortedLinkedList();
        $list->insert(3);
        $list->insert(1);
        $list->insert(2);

        $values = [];
        foreach ($list as $value) {
            $values[] = $value;
        }

        $this->assertSame([1, 2, 3], $values);
    }

    public function testFromArray(): void
    {
        $intList = SortedLinkedList::fromArray([9, 3, 7]);
        $this->assertSame([3, 7, 9], $intList->toArray());

        $strList = SortedLinkedList::fromArray(["pear", "apple", "banana"]);
        $this->assertSame(["apple", "banana", "pear"], $strList->toArray());
    }
}
