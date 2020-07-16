<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Catalog;

use Inkl\GoogleTagManager\Helper\Config\DataLayerCatalogConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Framework\App\Request\Http as HttpRequest;

class SearchKeyword
{
    /** @var DataLayerCatalogConfig */
    private $dataLayerCatalogConfig;
    /** @var RouteHelper */
    private $routeHelper;
    /** @var HttpRequest */
    private $httpRequest;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerCatalogConfig $dataLayerCatalogConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 * @param HttpRequest $httpRequest
	 */
    public function __construct(DataLayerCatalogConfig $dataLayerCatalogConfig,
                                DataLayer $dataLayer,
                                RouteHelper $routeHelper,
                                HttpRequest $httpRequest
    )
    {
        $this->dataLayerCatalogConfig = $dataLayerCatalogConfig;
        $this->routeHelper = $routeHelper;
        $this->httpRequest = $httpRequest;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $this->dataLayer->add('searchKeyword', $this->httpRequest->getParam('q'));
    }

    private function isEnabled()
    {
        return $this->dataLayerCatalogConfig->isSearchKeywordEnabled() && $this->routeHelper->isSearch();
    }

}