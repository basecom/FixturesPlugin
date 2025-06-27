<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Salutation\SalutationCollection;
use Shopware\Core\System\Salutation\SalutationEntity;

/**
 * This class provides utility methods to work with salutations. It has build in caching to prevent
 * multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->Salutation()->……();
 * ```
 */
readonly class SalutationUtils
{
    /**
     * @param EntityRepository<SalutationCollection> $salutationRepository
     */
    public function __construct(
        private EntityRepository $salutationRepository,
    ) {
    }

    public function getNotSpecifiedSalutation(): ?SalutationEntity
    {
        return once(function (): ?SalutationEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('salutationKey', 'not_specified'),
            )->setLimit(1);

            $criteria->setTitle(\sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $salutation = $this->salutationRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $salutation instanceof SalutationEntity ? $salutation : null;
        });
    }
}
