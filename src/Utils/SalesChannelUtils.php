<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Currency\CurrencyCollection;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\SalesChannel\SalesChannelCollection;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\System\Tax\TaxCollection;
use Shopware\Core\System\Tax\TaxEntity;

/**
 * This class provides utility methods to work with sales channels. It has build in caching to prevent
 * multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->SalesChannel()->……();
 * ```
 */
readonly class SalesChannelUtils
{
    /**
     * @param EntityRepository<SalesChannelCollection> $salesChannelRepository
     * @param EntityRepository<TaxCollection>          $taxRepository
     * @param EntityRepository<CurrencyCollection>     $currencyRepository
     */
    public function __construct(
        private EntityRepository $salesChannelRepository,
        private EntityRepository $taxRepository,
        private EntityRepository $currencyRepository,
    ) {
    }

    /**
     * Return the first sales channel with type "Storefront" or null if non was found.
     */
    public function getStorefrontSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_STOREFRONT);
    }

    /**
     * Return the first sales channel with type "headless" or null if non was found.
     */
    public function getHeadlessSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_API);
    }

    /**
     * Return the first sales channel with type "Product Comparison" or null if non was found.
     */
    public function getProductComparisonSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_PRODUCT_COMPARISON);
    }

    public function getSalesChannelByType(string $salesChannelType): ?SalesChannelEntity
    {
        return once(function () use ($salesChannelType): ?SalesChannelEntity {
            $criteria = (new Criteria())
                ->addFilter(new EqualsFilter('typeId', $salesChannelType))
                ->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $salesChannel = $this->salesChannelRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $salesChannel instanceof SalesChannelEntity ? $salesChannel : null;
        });
    }

    // TODO: Move to CurrencyUtils
    public function getCurrencyEuro(): ?CurrencyEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('isoCode', 'EUR'))
            ->setLimit(1);

        $currency = $this->currencyRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $currency instanceof CurrencyEntity ? $currency : null;
    }

    // TODO: Move to TaxUtils
    public function getTax19(): ?TaxEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('taxRate', 19))
            ->setLimit(1);

        $tax = $this->taxRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $tax instanceof TaxEntity ? $tax : null;
    }

    // TODO: Move to TaxUtils
    public function getTax(float $taxValue): ?TaxEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('taxRate', $taxValue))
            ->setLimit(1);

        $tax = $this->taxRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $tax instanceof TaxEntity ? $tax : null;
    }
}
