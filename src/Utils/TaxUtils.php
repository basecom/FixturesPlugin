<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Tax\TaxCollection;
use Shopware\Core\System\Tax\TaxEntity;

/**
 * This class provides utility methods to work with taxes. It has build in caching
 * to prevent multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->Tax()->……();
 * ```
 */
class TaxUtils
{
    /**
     * @param EntityRepository<TaxCollection> $taxRepository
     */
    public function __construct(
        private EntityRepository $taxRepository,
    ) {
    }

    /**
     * Return the tax entity with a tax rate of 19% or null if none exists.
     */
    public function getTax19(): ?TaxEntity
    {
        return $this->getTax(19);
    }

    /**
     * Return a tax entity with a specific tax rate or null if none exists.
     */
    public function getTax(float $taxRate): ?TaxEntity
    {
        return once(function () use ($taxRate): ?TaxEntity {
            $criteria = (new Criteria())
                ->addFilter(new EqualsFilter('taxRate', $taxRate))
                ->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $tax = $this->taxRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $tax instanceof TaxEntity ? $tax : null;
        });
    }
}
