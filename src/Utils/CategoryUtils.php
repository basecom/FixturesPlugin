<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CategoryUtils
{
    private EntityRepositoryInterface $categoryRepository;

    public function __construct(EntityRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getRootCategory(): ?CategoryEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('autoIncrement', 1))
            ->addFilter(new EqualsFilter('level', 1))
            ->setLimit(1);

        return $this->categoryRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    public function getFirst(): ?CategoryEntity
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('level', '1')
        )->setLimit(1);

        return $this->categoryRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    /**
     * Gets the first found category with the provided name.
     */
    public function getByName(string $name): ?CategoryEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));
        $criteria->setLimit(1);

        return $this->categoryRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }
}
