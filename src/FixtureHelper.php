<?php

declare(strict_types=1);

namespace Basecom\FixturePlugin;

use Basecom\FixturePlugin\Utils\CategoryUtils;
use Basecom\FixturePlugin\Utils\CmsUtils;
use Basecom\FixturePlugin\Utils\CustomerUtils;
use Basecom\FixturePlugin\Utils\MediaUtils;
use Basecom\FixturePlugin\Utils\PaymentMethodUtils;
use Basecom\FixturePlugin\Utils\SalesChannelUtils;
use Basecom\FixturePlugin\Utils\ShippingMethodUtils;

class FixtureHelper
{
    private MediaUtils $mediaUtils;
    private CategoryUtils $categoryUtils;
    private SalesChannelUtils $salesChannelUtils;
    private CmsUtils $cmsUtils;
    private PaymentMethodUtils $paymentMethodUtils;
    private ShippingMethodUtils $shippingMethodUtils;
    private CustomerUtils $customerUtils;

    public function __construct(MediaUtils $mediaUtils, CategoryUtils $categoryUtils, SalesChannelUtils $salesChannelUtils, CmsUtils $cmsUtils, PaymentMethodUtils $paymentMethodUtils, ShippingMethodUtils $shippingMethodUtils, CustomerUtils $customerUtils)
    {
        $this->mediaUtils          = $mediaUtils;
        $this->categoryUtils       = $categoryUtils;
        $this->salesChannelUtils   = $salesChannelUtils;
        $this->cmsUtils            = $cmsUtils;
        $this->paymentMethodUtils  = $paymentMethodUtils;
        $this->shippingMethodUtils = $shippingMethodUtils;
        $this->customerUtils       = $customerUtils;
    }

    /**
     * Use this to access the media related features
     * of the fixture helper class.
     */
    public function Media(): MediaUtils
    {
        return $this->mediaUtils;
    }

    /**
     * Use this to access the category related features
     * of the fixture helper class.
     */
    public function Category(): CategoryUtils
    {
        return $this->categoryUtils;
    }

    /**
     * Use this to access the sales channel related features
     * of the fixture helper class.
     */
    public function SalesChannel(): SalesChannelUtils
    {
        return $this->salesChannelUtils;
    }

    /**
     * Use this to access the customer related features
     * of the fixture helper class.
     */
    public function Customer(): CustomerUtils
    {
        return $this->customerUtils;
    }

    /**
     * Use this to access the cms related features
     * of the fixture helper class.
     */
    public function Cms(): CmsUtils
    {
        return $this->cmsUtils;
    }

    /**
     * Use this to access the payment method related features
     * of the fixture helper class.
     */
    public function PaymentMethod(): PaymentMethodUtils
    {
        return $this->paymentMethodUtils;
    }

    /**
     * Use this to access the shipping method related features
     * of the fixture helper class.
     */
    public function ShippingMethod(): ShippingMethodUtils
    {
        return $this->shippingMethodUtils;
    }
}
