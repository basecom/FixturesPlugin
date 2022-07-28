<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;

class CustomerFixture extends Fixture
{
    private const CUSTOMER_ID = '0d8eefdd6d32456385580e2ff42431b9';
    private const ADDRESS_ID  = 'e27dc2b4e85f4a0f9a912a09f07701b0';

    private FixtureHelper $helper;
    private EntityRepositoryInterface $customerRepository;

    public function __construct(FixtureHelper $helper, EntityRepositoryInterface $customerRepository)
    {
        $this->helper             = $helper;
        $this->customerRepository = $customerRepository;
    }

    public function load(FixtureBag $bag): void
    {
        $salesChannel = $this->helper->SalesChannel()->getStorefrontSalesChannel();

        $this->customerRepository->upsert([[
            'id'                     => self::CUSTOMER_ID,
            'salesChannelId'         => $salesChannel->getId(),
            'groupId'                => $salesChannel->getCustomerGroupId(),
            'defaultPaymentMethodId' => $this->helper->PaymentMethod()->getInvoicePaymentMethod()->getId(),
            'defaultBillingAddress'  => [
                'id'           => self::ADDRESS_ID,
                'salutationId' => $this->helper->Customer()->getNotSpecifiedSalutation()->getId(),
                'firstName'    => 'John',
                'lastName'     => 'Doe',
                'zipcode'      => '1234',
                'street'       => 'Sample Street',
                'city'         => 'Berlin',
                'countryId'    => $this->helper->SalesChannel()->getCountry('DE')->getId(),
            ],
            'defaultShippingAddressId' => self::ADDRESS_ID,
            'salutationId'             => $this->helper->Customer()->getNotSpecifiedSalutation()->getId(),
            'customerNumber'           => '1122',
            'firstName'                => 'John',
            'lastName'                 => 'Doe',
            'email'                    => 'test@shopware.dev',
            'password'                 => 'notset',
        ]], Context::createDefaultContext());
    }
}
