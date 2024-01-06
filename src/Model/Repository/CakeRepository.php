<?php

namespace CakeDDD\Repository\Model\Repository;

use Cake\Core\InstanceConfigTrait;
use Cake\Database\Query;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use CakeDDD\Repository\Model\Entity\CakeEntity;
use TypeError;

/**
 * @template TDomainEntity Domain entity class name
 */
abstract class CakeRepository
{
    use InstanceConfigTrait;

    private array $_defaultConfig = [];
    private CakeEntity $mapper;
    private Query $queryBuilder;
    private Table $table;

    /**
     * Constructor
     * @param string $tableAlias CakePHP Table alias that will be retrieved with TableLocator
     */
    public function __construct(string $tableAlias)
    {
        $this->table = TableRegistry::getTableLocator()->get($tableAlias);
        $this->queryBuilder = $this->table->selectQuery();
        $this->mapper = $this->getMapper();
    }

    /**
     * Class mapping domain and CakePHP entities
     * @return CakeEntity
     */
    private function getMapper(): CakeEntity
    {
        $mapper = $this->getConfig('mapper', $this->table->getEntityClass());
        $mapper = new $mapper();

        if ($mapper instanceof CakeEntity) {
            return $mapper;
        }

        throw new TypeError("Entity mapper must inherit class CakeEntity");
    }

    /**
     * Returns a cloned instance of the query builder
     *
     * Use this to perform select queries.
     *
     * @return SelectQuery
     */
    protected function query(): SelectQuery
    {
        return clone $this
            ->queryBuilder
            ->formatResults(
                fn (ResultSet $results) => array_map(
                    [$this, 'convertDomain'], $results->toArray()
                )
            );
    }

    /**
     * Transform CakePHP entity to domain entity
     * @param CakeEntity $entity
     * @return TDomainEntity
     */
    protected function convertDomain(CakeEntity $entity): object
    {
        return $entity->toDomain();
    }

    /**
     * Transform domain entity to CakePHP entity
     * @param TDomainEntity $entity
     * @return CakeEntity
     */
    protected function convertEntity(mixed $entity): CakeEntity
    {
        return $this->mapper::toRepository($entity);
    }

    /**
     * We allow cloning only from this scope.
     * Also, we always clone the query builder.
     */
    protected function __clone()
    {
        $this->queryBuilder = clone $this->queryBuilder;
    }

    /**
     * Begin a transactional query
     */
    public function begin(): void
    {
        $this->table->getConnection()->begin();
    }

    /**
     * Commit a transactional query
     *
     * @return bool
     */
    public function commit(): bool
    {
        return $this->table->getConnection()->commit();
    }

    /**
     * Rollback a transactional query
     *
     * @return bool
     */
    public function rollback(): bool
    {
        return $this->table->getConnection()->rollback();
    }

    /**
     * @return Table
     */
    public function getTable(): Table
    {
        return $this->table;
    }

    /**
     * Persist (save) a domain entity
     * @param TDomainEntity $entity Domain entity to save
     * @param ?bool $new Mark entity as new or check if exists when null
     * @return TDomainEntity
     */
    public function persist(mixed $entity, ?bool $new = null): object
    {
        $model = $this->convertEntity($entity);

        $isNew = $model->id === null;
        if ($new === null && !$isNew) {
            $isNew = $this->checkExisting($model);
        } else {
            $isNew = $new;
        }

        $model->setNew($isNew);

        return $this->table->save($model)->toDomain();
    }

    /**
     * Delete a domain entity
     * @param TDomainEntity $entity Domain entity to delete
     * @return bool
     */
    public function remove(mixed $entity): bool
    {
        $model = $this->convertEntity($entity);
        $model->setNew(false);

        return $this->table->delete($model);
    }

    /**
     * Check if the given entity exists
     * @param CakeEntity $entity Entity to check existence
     * @return bool
     */
    private function checkExisting(CakeEntity $entity): bool
    {
        $primaryColumns = (array)$this->table->getPrimaryKey();

        $alias = $this->table->getAlias();
        $conditions = [];
        foreach ($entity->extract($primaryColumns) as $k => $v) {
            $conditions["$alias.$k"] = $v;
        }

        return !$this->table->exists($conditions);
    }
}
