# CakePHP Domain Driven Design - Repository pattern
Part of the CakePHP Domain Driven Design Building Blocks suite.

Compatible with CakePHP 4.5 and CakePHP 5.

## Key Objectives
- Ease creation of Repositories
- Conserves the power of CakePHP ORM
- Respect Layered architecture. This plugin takes place in the Infrastructure layer.
- Respect CakePHP conventions
- Applies CakePHP Convention over Configuration philosophy

## Setup
Your entity must extends `CakeDDD\Repository\Model\Entity\CakeEntity` class. 
This maps (converts) CakePHP entities with your Domain entities. The `CakeRepository` takes care of this convertion.

## Usage
Create a repository class, I would suggest in `App\Model\Repository` namespace.
Implements your domain repository and extends with `CakeRepository`.
No changes needed in your `Table` classes.

### Entities
Extends your CakePHP entity with `CakeDDD\Repository\Model\Entity\CakeEntity` instead of `Cake\ORM\Entity`
and declare `toDomain` and `toRepository` methods.
To ease type auto-completion, you can add `@extends CakeEntity<YourDomainEntityClassName>` in head comments.
```php
// App\Model\Entity

/**
 * @extends CakeEntity<YourDomainEntity>
 */
class YourEntity
    extends CakeDDD\Repository\Model\Entity\CakeEntity {
    // Usual CakePHP entity definition
    ...

    public static function toRepository(mixed $domainEntity): static {
        return new self([
            // Map CakePHP entity fields with domain entity properties
            'id' => $domainEntity->getId(),
            ...
        ]);
    }
    
    public function toDomain(): object
    {
        return new FakeEntity(
            // Map domain entity properties with CakePHP entity fields 
            $this->get('id')
            ...
        );
    }
}
```

### Repositories
Create a repository class extending `CakeRepository`.
To ease type auto-completion, you can add `@extends CakeRepository<YourDomainEntityClassName>` in head comments.

The Repository constructor requires the CakePHP alias table that will be retrieved with `TableLocator`.
```php
 /**
  * @extends CakeRepository<YourDomainEntityClassName>
  */
class YourRepository
    extends CakeDDD\Repository\Model\Repository\CakeRepository
        implements YourDomainRepositoryInterface {
    public function __construct() {
        parent::__construct('App\Model\Table\YourCakePHPTable');
    }

    public function get(string $id): YourDomainEntity {
        return $this->query()->where('id' => $id)->first();
    }

    public function save(YourDomainEntity $domain): YourDomainEntity {
        return parent::persist($domain);
    }
    
    public function delete(YourDomainEntity $domain): bool {
        return parent::remove($domain);
    }
}
```

Method `CakeRepository::query()` converts CakePHP entities to domain entities. 


#### Caveats
Your repository can not declare methods named `persist` nor `remove` as they are already declared in `CakeRepository` class.
It is not allowed to override methods with varrying definitions.