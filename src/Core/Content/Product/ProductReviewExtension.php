<?php

namespace Sondoong\SondoongProductReview\Core\Content\Product;

use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityExtension;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\CascadeDelete;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Sondoong\SondoongProductReview\Core\Sondoong\SondoongDefinition;

class ProductReviewExtension extends EntityExtension
{
    public function extendFields(FieldCollection $collection): void
    {
        $collection->add(
            (new OneToOneAssociationField(
                'sondoong',
                'id',
                'product_review_id',
                SondoongDefinition::class
            ))->addFlags(new CascadeDelete()),
        );
    }

    public function getDefinitionClass(): string
    {
        return ProductReviewDefinition::class;
    }
}
