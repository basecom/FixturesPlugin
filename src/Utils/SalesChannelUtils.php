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
use Shopware\Core\System\SalesChannel\SalesChannelEntity;
use Shopware\Core\System\Snippet\Aggregate\SnippetSet\SnippetSetEntity;
use Shopware\Core\System\Tax\TaxEntity;

class SalesChannelUtils
{
    private EntityRepository $salesChannelRepository;
    private EntityRepository $snippetSetRepository;
    private EntityRepository $taxRepository;
    private EntityRepository $countryRepository;
    private EntityRepository $languageRepository;
    private EntityRepository $currencyRepository;

    public function __construct(EntityRepository $salesChannelRepository, EntityRepository $snippetSetRepository, EntityRepository $taxRepository, EntityRepository $countryRepository, EntityRepository $languageRepository, EntityRepository $currencyRepository)
    {
        $this->salesChannelRepository = $salesChannelRepository;
        $this->snippetSetRepository   = $snippetSetRepository;
        $this->taxRepository          = $taxRepository;
        $this->countryRepository      = $countryRepository;
        $this->languageRepository     = $languageRepository;
        $this->currencyRepository     = $currencyRepository;
    }

    public function getStorefrontSalesChannel(): ?SalesChannelEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('typeId', Defaults::SALES_CHANNEL_TYPE_STOREFRONT))
            ->setLimit(1);

        return $this->salesChannelRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    public function getCurrencyEuro(): ?CurrencyEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('isoCode', 'EUR'))
            ->setLimit(1);

        return $this->currencyRepository
            ->search(
                $criteria,
                Context::createDefaultContext()
            )->first();
    }

    public function getLanguage(string $languageName): ?LanguageEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('name', $languageName)
        )->setLimit(1);

        return $this->languageRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    public function getCountry(string $countryIso): ?CountryEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryIso)
        )->setLimit(1);

        return $this->countryRepository
            ->search(
                $criteria,
                Context::createDefaultContext()
            )->first();
    }

    public function getSnippetSet(string $countryCodeIso): ?SnippetSetEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryCodeIso)
        )->setLimit(1);

        return $this->snippetSetRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    public function getTax19(): ?TaxEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('taxRate', 19))
            ->setLimit(1);

        return $this->taxRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }
}
