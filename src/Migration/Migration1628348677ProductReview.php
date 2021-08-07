<?php declare(strict_types=1);

namespace Sondoong\SondoongProductReview\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1628348677ProductReview extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1628348677;
    }

    public function update(Connection $connection): void
    {
        $connection->executeUpdate('
            CREATE TABLE IF NOT EXISTS `sondoong_product_review` (
              `id` BINARY(16) NOT NULL,
              `is_purchased` TINYINT(1) NOT NULL DEFAULT 0,
              `product_review_id` BINARY(16) NOT NULL,
              `created_at` DATETIME(3) NOT NULL,
              `updated_at` DATETIME(3) NULL,
              PRIMARY KEY (`id`),
              CONSTRAINT `uniq.sondoong_product_review.product_review_id` UNIQUE (`product_review_id`),  
              CONSTRAINT `fk.sondoong_product_review.product_review_id` FOREIGN KEY (`product_review_id`)  
              REFERENCES `product_review` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
