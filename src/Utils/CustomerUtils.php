<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\Salutation\SalutationEntity;

class CustomerUtils
{
    private EntityRepositoryInterface $salutationRepository;

    public function __construct(EntityRepositoryInterface $salutationRepository)
    {
        $this->salutationRepository = $salutationRepository;
    }

    public function getNotSpecifiedSalutation(): ?SalutationEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('salutationKey', 'not_specified')
        )->setLimit(1);

        return $this->salutationRepository
            ->search(
                $criteria,
                Context::createDefaultContext()
            )->first();
    }
}
