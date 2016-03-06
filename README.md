# Doctrine specification

[![Build Status](https://travis-ci.org/gbprod/doctrine-specification.svg?branch=master)](https://travis-ci.org/gbprod/doctrine-specification) [![Coverage Status](https://coveralls.io/repos/github/gbprod/doctrine-specification/badge.svg?branch=master)](https://coveralls.io/github/gbprod/doctrine-specification?branch=master) [![Code Climate](https://codeclimate.com/github/gbprod/doctrine-specification/badges/gpa.svg)](https://codeclimate.com/github/gbprod/doctrine-specification)

[![Latest Stable Version](https://poser.pugx.org/gbprod/doctrine-specification/v/stable)](https://packagist.org/packages/gbprod/doctrine-specification) [![Total Downloads](https://poser.pugx.org/gbprod/doctrine-specification/downloads)](https://packagist.org/packages/gbprod/doctrine-specification) [![Latest Unstable Version](https://poser.pugx.org/gbprod/doctrine-specification/v/unstable)](https://packagist.org/packages/gbprod/doctrine-specification) [![License](https://poser.pugx.org/gbprod/doctrine-specification/license)](https://packagist.org/packages/gbprod/doctrine-specification)

This library allows you to write Doctrine ORM queries using the [specification pattern](http://en.wikipedia.org/wiki/Specification_pattern).

## Usage

You can write specifications using [gbprod/specification](https://github.com/gbprod/specification) library.

### Creates a doctrine specification filter

```php
namespace GBProd\Acme\Doctrine\SpecificationBuilder;

use GBProd\DoctrineSpecification\ExpressionBuilder\Builder;
use GBProd\Specification\Specification;
use Doctrine\ORM\QueryBuilder;

class IsAvailableBuilder implements Builder
{
    public function build(Specification $spec, QueryBuilder $qb)
    {
        return $qb->expr()
            ->andx(
                $qb->expr()->eq('available', "0"),
                $qb->expr()->gt('limitDate', "2016-03-05 00:00:00"),
            )
        ;
    }
}
```

### Configure

```php
$registry = new GBProd\DoctrineSpecification\Registry();

$handler = new GBProd\DoctrineSpecification\Handler($registry);
$handler->registerBuilder(IsAvailable::class, new IsAvailableBuilder());
$handler->registerBuilder(StockGreaterThan::class, new StockGreaterThanBuilder());
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
    ->getRepository('GBProd\Acme\CoreDomain\Product\Product')
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