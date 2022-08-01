<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Content\Cms\CmsPageEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class CmsUtils
{
    private EntityRepositoryInterface $cmsPageRepository;

    public function __construct(EntityRepositoryInterface $cmsPageRepository)
    {
        $this->cmsPageRepository = $cmsPageRepository;
    }

    public function getDefaultCategoryLayout(): ?CmsPageEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('locked', '1'))
            ->addFilter(new EqualsAnyFilter('translations.name', ['Default category layout', 'Default listing layout']))
            ->setLimit(1);

        return $this->cmsPageRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }
}
