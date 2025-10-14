<?php

namespace Xenus;

use IteratorIterator;
use MongoDB\BSON\Int64;
use MongoDB\Driver\CursorId;
use MongoDB\Driver\CursorInterface;
use MongoDB\Driver\Server;

class Cursor extends IteratorIterator implements CursorInterface
{
    use Concerns\HasCollection;


    public function key(): ?int
    {
        return parent::key();
    }

    /**
     * Return the current iterator element
     *
     * @return mixed
     */
    public function current(): object|array|null
    {
        $document = parent::current();

        if ($document instanceof Document && isset($this->collection)) {
            $document->connect($this->collection);
        }

        return $document;
    }

    /**
     * Transform the Cursor into an array
     *
     * @return array
     */
    public function toArray(): array

    {
        $documents = [];

        foreach ($this as $document) {
            $documents[] = $document;
        }

        return $documents;
    }

    /**
     * Forward methods calls to the inner iterator
     *
     * @param  string $name
     * @param  array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->getInnerIterator(), $name], $arguments);
    }

    public function getId(): Int64
    {
        return $this->getInnerIterator()->getId();
    }

    public function getServer(): Server
    {
        return $this->getInnerIterator()->getServer();
    }

    public function isDead(): bool
    {
        return $this->getInnerIterator()->isDead();
    }

    public function setTypeMap(array $typemap): void
    {
        $this->getInnerIterator()->setTypeMap($typemap);
    }
}
