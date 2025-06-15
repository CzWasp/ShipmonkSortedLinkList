<?php

declare(strict_types=1);

namespace Shipmonk;

use Countable;
use IteratorAggregate;
use ArrayIterator;
use Traversable;
use InvalidArgumentException;

/**
 * @template T of int|string
 * @implements IteratorAggregate<T>
 */
class SortedLinkedList implements IteratorAggregate, Countable
{
    /** @var Item<T>|null */
    private ?Item $head = null;

    /** @var int<0, max> */
    private int $count = 0;

    /** @var 'integer'|'string'|null */
    private ?string $type = null;

    /**
     * @param T $value
     */
    public function insert(int|string $value): void
    {
        $this->preflightTypeCheck($value);
        $newItem = new Item($value);

        if ($this->head === null || $this->compare($value, $this->head->value) < 0) {
            $newItem->next = $this->head;
            $this->head = $newItem;
        } else {
            $current = $this->head;
            while ($current->next !== null && $this->compare($current->next->value, $value) <= 0) {
                $current = $current->next;
            }
            $newItem->next = $current->next;
            $current->next = $newItem;
        }

        $this->count++;
    }

    /**
     * @param T $value
     * @return bool
     */
    public function remove(int|string $value): bool
    {
        if ($this->head === null) {
            return false;
        }

        if ($this->compare($this->head->value, $value) === 0) {
            $this->head = $this->head->next;
            $this->decrementCount();
            return true;
        }

        $prev = $this->head;
        $current = $this->head->next;

        while ($current !== null) {
            if ($this->compare($current->value, $value) === 0) {
                $prev->next = $current->next;
                $this->decrementCount();
                return true;
            }
            $prev = $current;
            $current = $current->next;
        }

        return false;
    }

    /**
     * @param T $value
     * @return bool
     */
    public function contains(int|string $value): bool
    {
        $item = $this->head;
        while ($item !== null) {
            if ($this->compare($item->value, $value) === 0) {
                return true;
            }
            $item = $item->next;
        }
        return false;
    }

    /**
     * @return T[]
     */
    public function toArray(): array
    {
        $arr = [];
        $item = $this->head;
        while ($item !== null) {
            $arr[] = $item->value;
            $item = $item->next;
        }
        return $arr;
    }

    /**
     * @param T[] $values
     * @return SortedLinkedList<int|string>
     */
    public static function fromArray(array $values): SortedLinkedList
    {
        $list = new self();
        foreach ($values as $value) {
            $list->insert($value);
        }
        return $list;
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * @return Traversable<T>
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->toArray());
    }

    /**
     * @param T $value
     */
    private function preflightTypeCheck(int|string $value): void
    {
        $valueType = gettype($value);

        if ($this->type === null) {
            $this->type = $valueType;
        } elseif ($this->type !== $valueType) {
            throw new InvalidArgumentException("Cannot mix types: expected {$this->type}, got {$valueType}.");
        }
    }

    /**
     * @param T $a
     * @param T $b
     */
    private function compare(int|string $a, int|string $b): int
    {
        return is_int($a) ? ($a <=> $b) : strcmp((string)$a, (string)$b);
    }

    /**
     * Decrements count without violating its non-negative constraint.
     */
    private function decrementCount(): void
    {
        if ($this->count > 0) {
            $this->count--;
        }
    }
}
