<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Currency\CurrencyCollection;
use Shopware\Core\System\Currency\CurrencyEntity;

/**
 * This class provides utility methods to work with currencies. It has build in caching to prevent
 * multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->Currency()->……();
 * ```
 */
class CurrencyUtils
{
    /**
     * @param EntityRepository<CurrencyCollection> $currencyRepository
     */
    public function __construct(
        private EntityRepository $currencyRepository,
    ) {
    }

    public function getCurrencyEuro(): ?CurrencyEntity
    {
        return once(function (): ?CurrencyEntity {
            $criteria = (new Criteria())
                ->addFilter(new EqualsFilter('isoCode', 'EUR'))
                ->setLimit(1);

            $criteria->setTitle(\sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $currency = $this->currencyRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $currency instanceof CurrencyEntity ? $currency : null;
        });
    }
}
