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
        EntityRepositoryInterface $countryRepository,
        EntityRepositoryInterface $categoryRepository,
        EntityRepositoryInterface $shippingMethodRepository,
        EntityRepositoryInterface $snippetSetRepository,
        EntityRepositoryInterface $languageRepository,
        EntityRepositoryInterface $cmsPageRepository
    ): void {
        $this->beConstructedWith(
            $currencyRepository,
            $paymentMethodRepository,
            $salutationRepository,
            $countryRepository,
            $categoryRepository,
            $shippingMethodRepository,
            $snippetSetRepository,
            $languageRepository,
            $cmsPageRepository
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
        $criteria = (new Criteria())->addFilter(new EqualsFilter('isoCode', 'EUR'))->setLimit(1);

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
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('handlerIdentifier', InvoicePayment::class))
            ->setLimit(1);

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
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('salutationKey', 'not_specified'))
            ->setLimit(1);

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
        $criteria = (new Criteria())->addFilter(new EqualsFilter('iso', 'DE'))->setLimit(1);

        $countryRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('germany-123');

        $this->getGermanCountryId()->shouldBe('germany-123');
    }

    public function it_can_return_the_country_id(
        EntityRepositoryInterface $countryRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('iso', 'EN'))->setLimit(1);

        $countryRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('english-123');

        $this->getCountryId('EN')->shouldBe('english-123');
    }

    public function it_can_return_the_first_category_id(
        EntityRepositoryInterface $categoryRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('level', '1'))->setLimit(1);

        $categoryRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('category-123');

        $this->getFirstCategoryId()->shouldBe('category-123');
    }

    public function it_can_return_the_first_shipping_method_id(
        EntityRepositoryInterface $shippingMethodRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('active', '1'))->setLimit(1);

        $shippingMethodRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('shipping-123');

        $this->getFirstShippingMethodId()->shouldBe('shipping-123');
    }

    public function it_can_return_the_de_snippet_set_id(
        EntityRepositoryInterface $snippetSetRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('iso', 'de-DE'))->setLimit(1);

        $snippetSetRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('snippet-123');

        $this->getDeSnippetSetId()->shouldBe('snippet-123');
    }

    public function it_can_return_the_snippet_set_id(
        EntityRepositoryInterface $snippetSetRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('iso', 'en-EN'))->setLimit(1);

        $snippetSetRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('snippet-en-321');

        $this->getSnippetSetId('en-EN')->shouldBe('snippet-en-321');
    }

    public function it_can_return_the_german_language_id(
        EntityRepositoryInterface $languageRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', 'Deutsch'))->setLimit(1);

        $languageRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('lang-id-deutsch');

        $this->getGermanLanguageId()->shouldBe('lang-id-deutsch');
    }

    public function it_can_return_the_language_id(
        EntityRepositoryInterface $languageRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())->addFilter(new EqualsFilter('name', 'English'))->setLimit(1);

        $languageRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('lang-id-english');

        $this->getLanguageId('English')
            ->shouldBe('lang-id-english');
    }

    public function it_can_return_the_default_category_layout_id(
        EntityRepositoryInterface $cmsPageRepository,
        IdSearchResult $searchResult
    ): void {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('locked', '1'))
            ->addFilter(new EqualsFilter('translations.name', 'Default category layout'))
            ->setLimit(1);

        $cmsPageRepository->searchIds(
            $criteria,
            Argument::type(Context::class)
        )->shouldBeCalledOnce()->willReturn($searchResult);

        $searchResult->firstId()->willReturn('cms-123');

        $this->getDefaultCategoryLayoutId()->shouldBe('cms-123');
    }
}
