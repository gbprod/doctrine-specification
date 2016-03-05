# Doctrine specification

[![Build Status](https://travis-ci.org/gbprod/doctrine-specification.svg?branch=master)](https://travis-ci.org/gbprod/doctrine-specification) [![Coverage Status](https://coveralls.io/repos/github/gbprod/doctrine-specification/badge.svg?branch=master)](https://coveralls.io/github/gbprod/doctrine-specification?branch=master) [![Code Climate](https://codeclimate.com/github/gbprod/doctrine-specification/badges/gpa.svg)](https://codeclimate.com/github/gbprod/doctrine-specification)

[![Latest Stable Version](https://poser.pugx.org/gbprod/doctrine-specification/v/stable)](https://packagist.org/packages/gbprod/doctrine-specification) [![Total Downloads](https://poser.pugx.org/gbprod/doctrine-specification/downloads)](https://packagist.org/packages/gbprod/doctrine-specification) [![Latest Unstable Version](https://poser.pugx.org/gbprod/doctrine-specification/v/unstable)](https://packagist.org/packages/gbprod/doctrine-specification) [![License](https://poser.pugx.org/gbprod/doctrine-specification/license)](https://packagist.org/packages/gbprod/doctrine-specification)

This library allows you to write Doctrine ORM queries using the [specification pattern](http://en.wikipedia.org/wiki/Specification_pattern).

## Usage

You can write specifications using [gbprod/specification](https://github.com/gbprod/specification) library.

### Creates a doctrine specification filter

```php
namespace GBProd\Acme\Doctrine\SpecificationHandler;

use GBProd\DoctrineSpecification\QueryModifier\Modifier;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

class IsAvailableSpecificationModifier implements Modifier
{
    public function filter(Specification $spec, QueryBuilder $qb)
    {
        return $qb
            ->where('available = 0')
            ->andWhere('limitDate < :now')
            ->setParameter('now', new \DateTime())
        ;
    }
}
```

### Configure

```php
$handler = new GBProd\DoctrineSpecification\Handler();
$handler->registerModifier(
    IsAvailable::class, // Specification full qualified classname
    new IsAvailableSpecificationModifier()
);
$handler->registerModifier(
    StockGreaterThan::class, // Specification full qualified classname
    new StockGreaterThanSpecificationModifier()
);
```

### Use it

```php
$available = new IsAvailable();
$hightStock = new StockGreaterThan(4);

$availableWithLowStock = $available
    ->andX(
        $hightStock->not()
    )
;

$qb = $this->em
    ->getRepository('GBProd\Acme\Product\Product')
    ->createQueryBuilder('p')
;

$result = $handler->handle($availableWithLowStock, $qb)
```

## Requirements

 * PHP 5.5+

## Installation

### Using composer

```bash
composer require gbprod/doctrine-specification
```