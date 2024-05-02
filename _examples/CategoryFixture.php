<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

readonly class CategoryFixture extends Fixture
{
    private FixtureHelper $helper;
    private EntityRepositoryInterface $categoryRepository;

    public function __construct(FixtureHelper $helper, EntityRepositoryInterface $categoryRepository)
    {
        $this->helper             = $helper;
        $this->categoryRepository = $categoryRepository;
    }

    public function load(): void
    {
        $this->categoryRepository->upsert([
            [
                'id'           => '0d8eefdd6d12456335280e2ff42431b9',
                'translations' => [
                    'de-DE' => [
                        'name' => 'Gutscheine',
                    ],
                    'en-GB' => [
                        'name' => 'Voucher',
                    ],
                ],
                'productAssignmentType' => 'product',
                'level'                 => 2,
                'active'                => true,
                'displayNestedProducts' => true,
                'visible'               => true,
                'type'                  => 'page',
                'cmsPageId'             => $this->helper->Cms()->getDefaultCategoryLayout()->getId(),
                'afterCategoryId'       => null,
                'parentId'              => $this->helper->Category()->getByName('Catalogue #1')->getId(),
            ],
        ], Context::createDefaultContext());
    }
}
