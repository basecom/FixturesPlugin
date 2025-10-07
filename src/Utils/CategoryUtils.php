<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Content\Category\CategoryCollection;
use Shopware\Core\Content\Category\CategoryEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * This class provides utility methods to work with categories. It has build in caching to prevent
 * multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->Category()->……();
 * ```
 */
readonly class CategoryUtils
{
    /**
     * @param EntityRepository<CategoryCollection> $categoryRepository
     */
    public function __construct(
        private EntityRepository $categoryRepository,
    ) {
    }

    /**
     * Gets the root category of the shop or.
     */
    public function getRootCategory(): ?CategoryEntity
    {
        return once(function (): ?CategoryEntity {
            $criteria = (new Criteria())
                ->addFilter(new EqualsFilter('autoIncrement', 1))
                ->addFilter(new EqualsFilter('level', 1))
                ->setLimit(1);

            $criteria->setTitle(\sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $category = $this->categoryRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $category instanceof CategoryEntity ? $category : null;
        });
    }
}
