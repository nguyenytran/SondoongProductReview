<?php

namespace Sondoong\SondoongProductReview\Core\Sondoong;

use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\OneToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class SondoongDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'sondoong_product_review';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return SondoongEntity::class;
    }

    public function getCollectionClass(): string
    {
        return SondoongCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            (new FkField('product_review_id', 'productReviewId', ProductReviewDefinition::class))->addFlags(new Required()),
            (new BoolField('is_purchased', 'isPurchased'))->addFlags(new Required()),
            new OneToOneAssociationField('productReview', 'product_review_id', 'id', ProductReviewDefinition::class, false),
        ]);
    }
}
