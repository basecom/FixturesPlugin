<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Currency\CurrencyEntity;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Locale\LocaleEntity;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\System\Snippet\Aggregate\SnippetSet\SnippetSetEntity;
use Shopware\Core\System\Tax\TaxEntity;

readonly class SalesChannelUtils
{
    public function __construct(
        private EntityRepository $salesChannelRepository,
        private EntityRepository $snippetSetRepository,
        private EntityRepository $taxRepository,
        private EntityRepository $countryRepository,
        private EntityRepository $languageRepository,
        private EntityRepository $currencyRepository,
        private EntityRepository $localeRepository,
    ) {
    }

    public function getStorefrontSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_STOREFRONT);
    }

    public function getHeadlessSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_API);
    }

    public function getProductComparisonSalesChannel(): ?SalesChannelEntity
    {
        return $this->getSalesChannelByType(Defaults::SALES_CHANNEL_TYPE_PRODUCT_COMPARISON);
    }

    public function getSalesChannelByType(string $salesChannelType): ?SalesChannelEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('typeId', $salesChannelType))
            ->setLimit(1);

        $salesChannel = $this->salesChannelRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $salesChannel instanceof SalesChannelEntity ? $salesChannel : null;
    }

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

    public function getLanguage(string $languageName): ?LanguageEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('name', $languageName),
        )->setLimit(1);

        $language = $this->languageRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $language instanceof LanguageEntity ? $language : null;
    }

    public function getLocale(string $code): ?LocaleEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('code', $code),
        )->setLimit(1);

        $locale = $this->localeRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $locale instanceof LocaleEntity ? $locale : null;
    }

    public function getCountry(string $countryIso): ?CountryEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryIso),
        )->setLimit(1);

        $country = $this->countryRepository
            ->search($criteria, Context::createDefaultContext(),
            )->first();

        return $country instanceof CountryEntity ? $country : null;
    }

    public function getSnippetSet(string $countryCodeIso): ?SnippetSetEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryCodeIso),
        )->setLimit(1);

        $snippetSet = $this->snippetSetRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $snippetSet instanceof SnippetSetEntity ? $snippetSet : null;
    }

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
