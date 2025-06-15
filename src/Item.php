<?php

declare(strict_types=1);

namespace Shipmonk;

/**
 * @template T of int|string
 */
final class Item
{
    /** @var T */
    public int|string $value;

    /** @var Item<T>|null */
    public ?Item $next = null;

    /**
     * @param T $value
     */
    public function __construct(int|string $value)
    {
        $this->value = $value;
    }
}
