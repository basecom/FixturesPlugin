<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Doctrine\Inflector\Language;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Country\CountryCollection;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Language\LanguageCollection;
use Shopware\Core\System\Language\LanguageEntity;
use Shopware\Core\System\Locale\LocaleCollection;
use Shopware\Core\System\Locale\LocaleEntity;
use Shopware\Core\System\Snippet\Aggregate\SnippetSet\SnippetSetCollection;
use Shopware\Core\System\Snippet\Aggregate\SnippetSet\SnippetSetEntity;

/**
 * This class provides utility methods to work with languages & locales. It has build in
 * caching to prevent multiple database queries for the same data within one command
 * execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->LanguageAndLocale()->……();
 * ```
 */
readonly class LanguageAndLocaleUtils
{
    /**
     * @param EntityRepository<SnippetSetCollection> $snippetSetRepository
     * @param EntityRepository<CountryCollection>    $countryRepository
     * @param EntityRepository<LanguageCollection>   $languageRepository
     * @param EntityRepository<LocaleCollection>     $localeRepository
     */
    public function __construct(
        private EntityRepository $snippetSetRepository,
        private EntityRepository $countryRepository,
        private EntityRepository $languageRepository,
        private EntityRepository $localeRepository,
    ) {
    }

    /**
     * Return a specific language by its name or null if its was not found.
     */
    public function getLanguage(string $languageName): ?LanguageEntity
    {
        return once(function () use ($languageName): ?LanguageEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('name', $languageName),
            )->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $language = $this->languageRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $language instanceof LanguageEntity ? $language : null;
        });
    }

    /**
     * Return a specific locale by its ISO code or null if its was not found.
     */
    public function getLocale(string $code): ?LocaleEntity
    {
        return once(function () use ($code): ?LocaleEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('code', $code),
            )->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $locale = $this->localeRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $locale instanceof LocaleEntity ? $locale : null;
        });
    }

    /**
     * Return a specific country by its ISO code or null if its was not found.
     */
    public function getCountry(string $countryIso): ?CountryEntity
    {
        return once(function () use ($countryIso): ?CountryEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('iso', $countryIso),
            )->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $country = $this->countryRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $country instanceof CountryEntity ? $country : null;
        });
    }

    /**
     * Return a specific snippet set by its country's ISO code or null if its was not found.
     */
    public function getSnippetSet(string $countryCodeIso): ?SnippetSetEntity
    {
        return once(function () use ($countryCodeIso): ?SnippetSetEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('iso', $countryCodeIso),
            )->setLimit(1);

            $criteria->setTitle(sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $snippetSet = $this->snippetSetRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $snippetSet instanceof SnippetSetEntity ? $snippetSet : null;
        });
    }
}
