<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Checkout\Shipping\ShippingMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

readonly class ShippingMethodUtils
{
    public function __construct(
        private EntityRepository $shippingMethodRepository
    ) {
    }

    public function getFirstShippingMethod(): ?ShippingMethodEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('active', '1')
        )->setLimit(1);

        $shippingMethod = $this->shippingMethodRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $shippingMethod instanceof ShippingMethodEntity ? $shippingMethod : null;
    }
}
