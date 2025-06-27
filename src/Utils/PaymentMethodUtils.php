<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin\Utils;

use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\InvoicePayment;
use Shopware\Core\Checkout\Payment\PaymentMethodCollection;
use Shopware\Core\Checkout\Payment\PaymentMethodEntity;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;

/**
 * This class provides utility methods to work with payment methods. It has build in caching to prevent
 * multiple database queries for the same data within one command execution / request.
 *
 * This class is designed to be used through the FixtureHelper, using:
 * ```php
 * $this->helper->PaymentMethod()->……();
 * ```
 */
readonly class PaymentMethodUtils
{
    /**
     * @param EntityRepository<PaymentMethodCollection> $paymentMethodRepository
     */
    public function __construct(
        private EntityRepository $paymentMethodRepository,
    ) {
    }

    /**
     * Return the default invoice payment method of shopware.
     */
    public function getInvoicePaymentMethod(): ?PaymentMethodEntity
    {
        return once(function (): ?PaymentMethodEntity {
            $criteria = (new Criteria())->addFilter(
                new EqualsFilter('handlerIdentifier', InvoicePayment::class),
            )->setLimit(1);

            $criteria->setTitle(\sprintf('%s::%s()', __CLASS__, __FUNCTION__));

            $paymentMethod = $this->paymentMethodRepository
                ->search($criteria, Context::createDefaultContext())
                ->first();

            return $paymentMethod instanceof PaymentMethodEntity ? $paymentMethod : null;
        });
    }
}
