<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\General;

use Inkl\GoogleTagManager\Helper\Config\DataLayerGeneralConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;

class PageType
{
    /** @var DataLayerGeneralConfig */
    protected $dataLayerGeneralConfig;
    /** @var RouteHelper */
    protected $routeHelper;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerGeneralConfig $dataLayerGeneralConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 */
    public function __construct(DataLayerGeneralConfig $dataLayerGeneralConfig,
                                DataLayer $dataLayer,
                                RouteHelper $routeHelper)
    {
        $this->dataLayerGeneralConfig = $dataLayerGeneralConfig;
        $this->routeHelper = $routeHelper;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $pageType = $this->determine();
        if ($pageType)
        {
            $this->dataLayer->add('pageType', $pageType, 'page_type');
        }
    }

    protected function determine()
    {
        if ($this->routeHelper->isHome()) return 'home';
        if ($this->routeHelper->isCategory()) return 'category';
        if ($this->routeHelper->isSearch()) return 'searchresults';
        if ($this->routeHelper->isProduct()) return 'product';
        if ($this->routeHelper->isCart()) return 'cart';
        if ($this->routeHelper->isPurchase()) return 'purchase';
        if ($this->routeHelper->isNotFound()) return 'notfound';

        return 'other';
    }

    protected function isEnabled()
    {
        return $this->dataLayerGeneralConfig->isPageTypeEnabled();
    }

}
