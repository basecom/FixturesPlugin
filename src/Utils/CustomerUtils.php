<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Salutation\SalutationCollection;
use Shopware\Core\System\Salutation\SalutationEntity;

readonly class CustomerUtils
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
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('salutationKey', 'not_specified'),
        )->setLimit(1);

        $salutation = $this->salutationRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();

        return $salutation instanceof SalutationEntity ? $salutation : null;
    }
}
