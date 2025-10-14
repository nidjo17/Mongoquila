<?php

namespace Xenus;

use MongoDB\BSON\ObjectID;
use MongoDB\Collection as BaseCollection;

use Xenus\CollectionConfiguration as Configuration;

class Collection extends BaseCollection

{
    use Concerns\HasEvents;
    use Concerns\HasConvenientWrites;
    use Concerns\HasCollectionResolver;

    /**
     * Hold the collection's configuration
     * @var Configuration
     */
    protected $configuration = null;

    /**
     * Construct a new collection
     *
     * @param Connection $connection
     * @param array      $properties
     */
    public function __construct(Connection $connection, array $properties = [])
    {
        $this->configuration = new Configuration($connection, $properties + ['name' => $this->name ?? null, 'document' => $this->document ?? null]);

        if ($this->configuration->has('name') === false) {
            throw new Exceptions\InvalidArgumentException('The collection\'s name must be defined');
        }

        parent::__construct(
            $connection->getManager(), $connection->getDatabaseName(), $this->configuration->getCollectionName(), $this->configuration->getCollectionOptions()
        );
    }

    /**
     * Find documents matching some filters
     *
     * @param  array            $filter
     * @param  array            $options
     *
     * @return \Xenus\Cursor
     */
    public function find($filter = [], array $options = []): \MongoDB\Driver\CursorInterface
    {
        return (new Cursor(parent::find($filter, $options)))->connect($this);
    }

    /**
     * Find one document matching an ID or some filters
     *
     * @param  array|ObjectID   $filter
     * @param  array            $options
     *
     * @return array|object|null
     */
    public function findOne($filter = [], array $options = []): object|array|null
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        $document = parent::findOne($filter, $options);

        if ($document instanceof Document) {
            $document->connect($this);
        }

        return $document;
    }

    /**
     * Find one document matching an ID or some filters and delete it
     *
     * @param  array|ObjectID   $filter
     * @param  array            $options
     *
     * @return array|object|null
     */
    public function findOneAndDelete($filter = [], array $options = []): object|array|null
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        $document = parent::findOneAndDelete($filter, $options);

        if ($document instanceof Document) {
            $document->connect($this);
        }

        return $document;
    }

    /**
     * Find one document matching an ID or some filters and update it
     *
     * @param  array|ObjectID   $filter
     * @param  array|object     $update
     * @param  array            $options
     *
     * @return array|object|null
     */
    public function findOneAndUpdate($filter, $update, array $options = []): object|array|null
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        $document = parent::findOneAndUpdate($filter, $update, $options);

        if ($document instanceof Document) {
            $document->connect($this);
        }

        return $document;
    }

    /**
     * Find one document matching an ID or some filters and replace it
     *
     * @param  array|ObjectID   $filter
     * @param  array|object     $replacement
     * @param  array            $options
     *
     * @return array|object|null
     */
    public function findOneAndReplace($filter, $replacement, array $options = []): object|array|null
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        $document = parent::findOneAndReplace($filter, $replacement, $options);

        if ($document instanceof Document) {
            $document->connect($this);
        }

        return $document;
    }

    /**
     * Delete one document matching an ID or some filters
     *
     * @param  array|ObjectID   $filter
     * @param  array            $options
     *
     * @return \MongoDB\DeleteResult
     */
    public function deleteOne($filter, array $options = []): \MongoDB\DeleteResult
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        return parent::deleteOne($filter, $options);
    }

    /**
     * Update one document matching an ID or some filters
     *
     * @param  array|ObjectID   $filter
     * @param  array|object     $update
     * @param  array            $options
     *
     * @return \MongoDB\UpdateResult
     */
    public function updateOne($filter, $update, array $options = []): \MongoDB\UpdateResult
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        return parent::updateOne($filter, $update, $options);
    }

    /**
     * Replace one document matching an ID or some filters
     *
     * @param  array|ObjectID   $filter
     * @param  array|object     $replacement
     * @param  array            $options
     *
     * @return \MongoDB\UpdateResult
     */
    public function replaceOne($filter, $replacement, array $options = []): \MongoDB\UpdateResult
    {
        if ($filter instanceof ObjectID) {
            $filter = ['_id' => $filter];
        }

        return parent::replaceOne($filter, $replacement, $options);
    }
}
