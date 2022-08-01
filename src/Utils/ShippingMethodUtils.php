<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Checkout\Shipping\ShippingMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class ShippingMethodUtils
{
    private EntityRepositoryInterface $shippingMethodRepository;

    public function __construct(EntityRepositoryInterface $shippingMethodRepository)
    {
        $this->shippingMethodRepository = $shippingMethodRepository;
    }

    public function getFirstShippingMethod(): ?ShippingMethodEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('active', '1')
        )->setLimit(1);

        return $this->shippingMethodRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }
}
