<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Checkout\Shipping\ShippingMethodCollection;
use Shopware\Core\Checkout\Shipping\ShippingMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * This class provides utility methods to work with shipping methods. It has build in caching
 * to prevent multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->ShippingMethod()->……();
 * ```
 */
readonly class ShippingMethodUtils
{
    /**
     * @param EntityRepository<ShippingMethodCollection> $shippingMethodRepository
     */
    public function __construct(
        private EntityRepository $shippingMethodRepository,
    ) {
    }

    /**
     * Returns the first active shipping method or null if none exists.
     */
    public function getFirstShippingMethod(): ?ShippingMethodEntity
    {
        return once(function (): ?ShippingMethodEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('active', '1'),
            )->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $shippingMethod = $this->shippingMethodRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $shippingMethod instanceof ShippingMethodEntity ? $shippingMethod : null;
        });
    }
}
