<?php

namespace Sondoong\SondoongProductReview\Core\Sondoong;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @method void              add(SondoongEntity $entity)
 * @method void              set(string $key, SondoongEntity $entity)
 * @method SondoongEntity[]    getIterator()
 * @method SondoongEntity[]    getElements()
 * @method SondoongEntity|null get(string $key)
 * @method SondoongEntity|null first()
 * @method SondoongEntity|null last()
 */
class SondoongCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return SondoongEntity::class;
    }
}
