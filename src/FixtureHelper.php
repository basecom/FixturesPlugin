<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\InvoicePayment;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

class FixtureHelper
{
    private EntityRepositoryInterface $currencyRepository;
    private EntityRepositoryInterface $paymentMethodRepository;
    private EntityRepositoryInterface $salutationRepository;
    private EntityRepositoryInterface $countryRepository;

    public function __construct(
        EntityRepositoryInterface $currencyRepository,
        EntityRepositoryInterface $paymentMethodRepository,
        EntityRepositoryInterface $salutationRepository,
        EntityRepositoryInterface $countryRepository
    ) {
        $this->currencyRepository      = $currencyRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->salutationRepository    = $salutationRepository;
        $this->countryRepository       = $countryRepository;
    }

    public function getEuroCurrencyId(): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('isoCode', 'EUR'));

        return $this->currencyRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getInvoicePaymentMethodId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('handlerIdentifier', InvoicePayment::class)
        );

        return $this->paymentMethodRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getNotSpecifiedSalutationId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('salutationKey', 'not_specified')
        );

        return $this->salutationRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getGermanCountryId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', 'DE')
        );

        return $this->countryRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }
}
