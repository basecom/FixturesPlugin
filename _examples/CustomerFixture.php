<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class CustomerFixture extends Fixture
{
    private const CUSTOMER_ID = '0d8eefdd6d32456385580e2ff42431b9';
    private const ADDRESS_ID  = 'e27dc2b4e85f4a0f9a912a09f07701b0';

    public function __construct(
        private readonly EntityRepository $customerRepository
    ) {
    }

    public function load(): void
    {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel();
        $this->helper->ensureNotEmpty($salesChannel);

        $this->customerRepository->upsert([[
            'id'                     => self::CUSTOMER_ID,
            'salesChannelId'         => $salesChannel->getId(),
            'groupId'                => $salesChannel->getCustomerGroupId(),
            'defaultPaymentMethodId' => $this->helper->PaymentMethod()->getInvoicePaymentMethod()?->getId(),
            'defaultBillingAddress'  => [
                'id'           => self::ADDRESS_ID,
                'salutationId' => $this->helper->Salutation()->getNotSpecifiedSalutation()?->getId(),
                'firstName'    => 'Zoey',
                'lastName'     => 'Smith',
                'zipcode'      => '1234',
                'street'       => 'Sample Street',
                'city'         => 'Berlin',
                'countryId'    => $this->helper->LanguageAndLocale()->getCountry('DE')?->getId(),
            ],
            'defaultShippingAddressId' => self::ADDRESS_ID,
            'salutationId'             => $this->helper->Salutation()->getNotSpecifiedSalutation()?->getId(),
            'customerNumber'           => '1122',
            'firstName'                => 'Zoey',
            'lastName'                 => 'Smith',
            'email'                    => 'test@shopware.dev',
            'password'                 => 'notset',
        ]], Context::createDefaultContext());
    }
}
