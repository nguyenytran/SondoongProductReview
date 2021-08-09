<?php

namespace Sondoong\SondoongProductReview\Subscriber;

use Shopware\Core\Checkout\Order\Aggregate\OrderLineItem\OrderLineItemCollection;
use Shopware\Core\Checkout\Order\OrderEntity;
use Shopware\Core\Checkout\Order\OrderStates;
use Shopware\Core\Content\Product\ProductEvents;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\EntityWriteResult;
use Shopware\Core\Framework\DataAbstractionLayer\Event\EntityWrittenEvent;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\MultiFilter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class ProductReviewSubscriber implements EventSubscriberInterface
{
    private EntityRepositoryInterface $sondoongRepository;

    private EntityRepositoryInterface $orderRepository;

    private SystemConfigService $systemConfigService;

    public function __construct(
        EntityRepositoryInterface $sondoongRepository,
        EntityRepositoryInterface $orderRepository,
        SystemConfigService $systemConfigService
    ) {
        $this->sondoongRepository = $sondoongRepository;
        $this->orderRepository = $orderRepository;
        $this->systemConfigService = $systemConfigService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductEvents::PRODUCT_REVIEW_WRITTEN_EVENT => [
                ['handleProductReviewWritten', 201]
            ],
        ];
    }

    public function handleProductReviewWritten(EntityWrittenEvent $entityWrittenEvent):void
    {
        $writeResults = $entityWrittenEvent->getWriteResults();
        $context = $entityWrittenEvent->getContext();
        $showPurchasedConfig = $this->systemConfigService->get(
            'SondoongProductReview.config.purchasedEnabled'
        );

        if (!$showPurchasedConfig || empty($writeResults) || $writeResults[0]->getOperation() === EntityWriteResult::OPERATION_UPDATE) {
            return;
        }

        $this->setIsPurchased($writeResults[0]->getPayload(), $context);
    }

    private function setIsPurchased(array $payload, $context)
    {
        $criteria = new Criteria();
        $criteria->addAssociation('stateMachineState');
        $criteria->addAssociation('lineItems.product');
        $criteria->addFilter(new MultiFilter(
            MultiFilter::CONNECTION_AND,
            [
                new EqualsFilter('orderCustomer.customerId', $payload['customerId']),
                new EqualsFilter('stateMachineState.technicalName', OrderStates::STATE_COMPLETED),
            ]
        ));

        $orders = $this->orderRepository->search($criteria, $context)->getEntities();

        if (empty($orders->getElements())) {
            return;
        }

        /** @var OrderEntity $order */
        foreach ($orders->getElements() as $order) {
            /** @var OrderLineItemCollection $lineItems */
            $lineItems = $order->getLineItems();

            if (empty($lineItems->getElements())) {
                continue;
            }

            $lineItems = $order->getLineItems()->getElements();

            foreach ($lineItems as $item) {
                if ($item->getProductId() !== $payload['productId']) {
                    continue;
                }

                $this->sondoongRepository->create([
                    ['productReviewId' => $payload['id'], 'isPurchased' => true]
                ], $context);

                break 2;
            }
        }
    }
}
