<?php

declare(strict_types=1);

namespace AdventOfCode\Helper;

class Block implements \ArrayAccess
{
    private $data = [];

    public function offsetSet(mixed $key, mixed $value): void
    {
        if ($key === null) {
            $this->data[] = $value;
        } else {
            $this->data[$key] = $value;
        }
    }

    public function offsetExists(mixed $key): bool
    {
        return isset($this->data[$key]);
    }

    public function offsetGet(mixed $key): mixed
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function offsetUnset(mixed $key): void
    {
        unset($this->data[$key]);
    }

    public function sum()
    {
        $sum = 0;

        foreach ($this->data as $item) {
            $sum += intval($item->value());
        }

        return $sum;
    }
}
