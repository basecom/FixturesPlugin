<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Basecom\FixturePlugin\Utils\MediaUtils;
use Shopware\Core\Checkout\Payment\Cart\PaymentHandler\InvoicePayment;
use Shopware\Core\Content\Media\Aggregate\MediaDefaultFolder\MediaDefaultFolderEntity;
use Shopware\Core\Defaults;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsAnyFilter;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\System\SalesChannel\SalesChannelEntity;

class FixtureHelper
{
    private EntityRepositoryInterface $currencyRepository;
    private EntityRepositoryInterface $paymentMethodRepository;
    private EntityRepositoryInterface $salutationRepository;
    private EntityRepositoryInterface $countryRepository;
    private EntityRepositoryInterface $categoryRepository;
    private EntityRepositoryInterface $shippingMethodRepository;
    private EntityRepositoryInterface $snippetSetRepository;
    private EntityRepositoryInterface $languageRepository;
    private EntityRepositoryInterface $cmsPageRepository;
    private EntityRepositoryInterface $taxRepository;
    private EntityRepositoryInterface $salesChannelRepository;
    private EntityRepositoryInterface $mediaFolderRepository;
    private MediaUtils $mediaUtils;

    public function __construct(
        EntityRepositoryInterface $currencyRepository,
        EntityRepositoryInterface $paymentMethodRepository,
        EntityRepositoryInterface $salutationRepository,
        EntityRepositoryInterface $countryRepository,
        EntityRepositoryInterface $categoryRepository,
        EntityRepositoryInterface $shippingMethodRepository,
        EntityRepositoryInterface $snippetSetRepository,
        EntityRepositoryInterface $languageRepository,
        EntityRepositoryInterface $cmsPageRepository,
        EntityRepositoryInterface $taxRepository,
        EntityRepositoryInterface $salesChannelRepository,
        EntityRepositoryInterface $mediaFolderRepository,
        MediaUtils $mediaUtils
    ) {
        $this->currencyRepository       = $currencyRepository;
        $this->paymentMethodRepository  = $paymentMethodRepository;
        $this->salutationRepository     = $salutationRepository;
        $this->countryRepository        = $countryRepository;
        $this->categoryRepository       = $categoryRepository;
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->snippetSetRepository     = $snippetSetRepository;
        $this->languageRepository       = $languageRepository;
        $this->cmsPageRepository        = $cmsPageRepository;
        $this->taxRepository            = $taxRepository;
        $this->salesChannelRepository   = $salesChannelRepository;
        $this->mediaFolderRepository    = $mediaFolderRepository;
        $this->mediaUtils               = $mediaUtils;
    }

    public function getEuroCurrencyId(): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('isoCode', 'EUR'))
            ->setLimit(1);

        return $this->currencyRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getInvoicePaymentMethodId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('handlerIdentifier', InvoicePayment::class)
        )->setLimit(1);

        return $this->paymentMethodRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getNotSpecifiedSalutationId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('salutationKey', 'not_specified')
        )->setLimit(1);

        return $this->salutationRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getGermanCountryId(): ?string
    {
        return $this->getCountryId('DE');
    }

    public function getFirstCategoryId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('level', '1')
        )->setLimit(1);

        return $this->categoryRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function getFirstShippingMethodId(): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('active', '1')
        )->setLimit(1);

        return $this->shippingMethodRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function getDeSnippetSetId(): ?string
    {
        return $this->getSnippetSetId('de-DE');
    }

    public function getGermanLanguageId(): ?string
    {
        return $this->getLanguageId('Deutsch');
    }

    public function getLanguageId(string $languageName): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('name', $languageName)
        )->setLimit(1);

        return $this->languageRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function getCountryId(string $countryIso): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryIso)
        )->setLimit(1);

        return $this->countryRepository->searchIds(
            $criteria,
            Context::createDefaultContext()
        )->firstId();
    }

    public function getSnippetSetId(string $countryCodeIso): ?string
    {
        $criteria = (new Criteria())->addFilter(
            new EqualsFilter('iso', $countryCodeIso)
        )->setLimit(1);

        return $this->snippetSetRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function getDefaultCategoryLayoutId(): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('locked', '1'))
            ->addFilter(new EqualsAnyFilter('translations.name', ['Default category layout', 'Default listing layout']))
            ->setLimit(1);

        return $this->cmsPageRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function get19TaxId(): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('taxRate', 19))
            ->setLimit(1);

        return $this->taxRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    public function getStorefrontSalesChannel(): ?SalesChannelEntity
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('typeId', Defaults::SALES_CHANNEL_TYPE_STOREFRONT))
            ->setLimit(1);

        return $this->salesChannelRepository
            ->search($criteria, Context::createDefaultContext())
            ->first();
    }

    /**
     * Copied from "vendor/shopware/core/Content/Media/MediaService.php".
     */
    public function getMediaDefaultFolderId(string $folderName): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('media_folder.defaultFolder.entity', $folderName))
            ->addAssociation('defaultFolder')
            ->setLimit(1);

        $defaultFolderResult = $this->mediaFolderRepository->search($criteria, Context::createDefaultContext());

        /** @var MediaDefaultFolderEntity|null $defaultFolder */
        $defaultFolder = $defaultFolderResult->first();

        return $defaultFolder ? $defaultFolder->getId() : null;
    }

    public function getCatalogueRootCategoryId(): ?string
    {
        $criteria = (new Criteria())
            ->addFilter(new EqualsFilter('autoIncrement', 1))
            ->addFilter(new EqualsFilter('level', 1))
            ->setLimit(1);

        return $this->categoryRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    /**
     * Gets the first ID of the first found category
     * with the provided name.
     */
    public function getCategoryIdByName(string $name): ?string
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('name', $name));
        $criteria->setLimit(1);

        return $this->categoryRepository
            ->searchIds($criteria, Context::createDefaultContext())
            ->firstId();
    }

    /**
     * Use this to access the media related features
     * of the fixture helper class.
     */
    public function Media(): MediaUtils
    {
        return $this->mediaUtils;
    }
}
