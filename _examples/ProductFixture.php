<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

readonly class ProductFixture extends Fixture
{
    private const MEDIA_ID   = '0d2ccefd6d22436385580e2ff42431b9';
    private const PRODUCT_ID = '0d8ffedd6d22436385580e2ff42431b9';
    private const COVER_ID   = '0d8fffdd6d33436353580e2ad42421b9';

    private FixtureHelper $helper;
    private EntityRepositoryInterface $repoProducts;

    public function __construct(FixtureHelper $helper, EntityRepositoryInterface $repoProducts)
    {
        $this->helper       = $helper;
        $this->repoProducts = $repoProducts;
    }

    public function load(): void
    {
        $this->helper->Media()->upload(
            self::MEDIA_ID,
            $this->helper->Media()->getDefaultFolder('product')->getId(),
            __DIR__.'/Assets/subscription-product.png',
            'png',
            'image/png',
        );

        $this->repoProducts->upsert([
            [
                'id'            => self::PRODUCT_ID,
                'name'          => 'Subscription Product',
                'taxId'         => $this->helper->SalesChannel()->getTax19()->getId(),
                'productNumber' => 'SUB-123',
                'description'   => 'Subscription Product for testing purpose in development environment.',
                'visibilities'  => [
                    [
                        'id'             => '0d8fffdd6d33436353580e2ad42431b9',
                        'salesChannelId' => $this->helper->SalesChannel()->getStorefrontSalesChannel()->getId(),
                        'visibility'     => 30,
                    ],
                ],
                'categories' => [
                    [
                        'id' => $this->helper->Category()->getByName('Clothing')->getId(),
                    ],
                ],
                'stock' => 10,
                'price' => [
                    [
                        'currencyId' => $this->helper->SalesChannel()->getCurrencyEuro()->getId(),
                        'gross'      => 19.99,
                        'net'        => 16.80,
                        'linked'     => true,
                    ],
                ],
                'customFields' => [
                    'my_field_abc' => true,
                ],
                'media' => [
                    [
                        'id'      => self::COVER_ID,
                        'mediaId' => self::MEDIA_ID,
                    ],
                ],
                'coverId' => self::COVER_ID,
            ],
        ],
            Context::createDefaultContext()
        );
    }
}
