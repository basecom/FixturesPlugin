<?php

declare(strict_types=1);

namespace spec\Basecom\FixturePlugin;

use Basecom\FixturePlugin\FixtureHelper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\InvoicePayment;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\IdSearchResult;

class FixtureHelperSpec extends ObjectBehavior
{
    public function let(
        EntityRepositoryInterface $currencyRepository,
        EntityRepositoryInterface $paymentMethodRepository,
        EntityRepositoryInterface $salutationRepository,
        EntityRepositoryInterface $countryRepository
    ): void {
        $this->beConstructedWith(
            $currencyRepository,
            $paymentMethodRepository,
            $salutationRepository,
            $countryRepository
        );
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(FixtureHelper::class);
    }

    public function it_can_return_the_euro_currency_id(
        EntityRepositoryInterface $currencyRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('isoCode', 'EUR'));

        $currencyRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('euro-123');

        $this->getEuroCurrencyId()->shouldBe('euro-123');
    }

    public function it_can_return_the_invoice_payment_method_id(
        EntityRepositoryInterface $paymentMethodRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('handlerIdentifier', InvoicePayment::class));

        $paymentMethodRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('invoice-123');

        $this->getInvoicePaymentMethodId()->shouldBe('invoice-123');
    }

    public function it_can_return_the_not_specified_salutation_id(
        EntityRepositoryInterface $salutationRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('salutationKey', 'not_specified'));

        $salutationRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('salutation-123');

        $this->getNotSpecifiedSalutationId()->shouldBe('salutation-123');
    }

    public function it_can_return_the_german_country_id(
        EntityRepositoryInterface $countryRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('iso', 'DE'));

        $countryRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('germany-123');

        $this->getGermanCountryId()->shouldBe('germany-123');
    }
}
