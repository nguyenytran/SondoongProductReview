<?php

namespace Sondoong\SondoongProductReview\Core\Sondoong;

use Shopware\Core\Content\Product\Aggregate\ProductReview\ProductReviewEntity;
use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class SondoongEntity extends Entity
{
    use EntityIdTrait;

    protected bool $isPurchased;

    protected ?ProductReviewEntity $productReview;

    public function getIsPurchased(): ?bool
    {
        return $this->isPurchased;
    }

    public function setIsPurchased(bool $isPurchased): void
    {
        $this->isPurchased = $isPurchased;
    }

    public function getProductReview(): ?ProductReviewEntity
    {
        return $this->productReview;
    }

    public function setProductReview(ProductReviewEntity $productReview): void
    {
        $this->productReview = $productReview;
    }
}
