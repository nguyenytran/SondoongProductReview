<?php declare(strict_types=1);

namespace Sondoong\SondoongProductReview;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Plugin;
use Shopware\Core\Framework\Plugin\Context\UninstallContext;

class SondoongProductReview extends Plugin
{
    public function uninstall(UninstallContext $context): void
    {
        parent::uninstall($context);

        if ($context->keepUserData()) {
            return;
        }

        $connection = $this->container->get(Connection::class);
        $connection->executeUpdate('DROP TABLE IF EXISTS `sondoong_product_review`');
    }
}
