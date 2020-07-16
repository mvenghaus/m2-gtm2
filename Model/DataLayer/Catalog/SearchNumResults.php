<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Catalog;

use Inkl\GoogleTagManager\Helper\Config\DataLayerCatalogConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Framework\View\LayoutInterface;

class SearchNumResults
{
	/** @var DataLayerCatalogConfig */
	private $dataLayerCatalogConfig;
	/** @var RouteHelper */
	private $routeHelper;
	/** @var LayoutInterface */
	private $layout;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerCatalogConfig $dataLayerCatalogConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 * @param LayoutInterface $layout
	 */
	public function __construct(DataLayerCatalogConfig $dataLayerCatalogConfig,
	                            DataLayer $dataLayer,
	                            RouteHelper $routeHelper,
	                            LayoutInterface $layout
	)
	{
		$this->dataLayerCatalogConfig = $dataLayerCatalogConfig;
		$this->routeHelper = $routeHelper;
		$this->layout = $layout;
		$this->dataLayer = $dataLayer;
	}

	public function handle()
	{
		if (!$this->isEnabled())
		{
			return;
		}

		$this->dataLayer->add('searchNumResults', $this->getSearchNumResults());
	}

	private function getSearchNumResults()
	{
		$searchProductListBlock = $this->layout->getBlock('search_result_list');
		if (!$searchProductListBlock) return 0;

		return $searchProductListBlock->getLoadedProductCollection()->getSize();
	}

	private function isEnabled()
	{
		return $this->dataLayerCatalogConfig->isSearchNumResultsEnabled() && $this->routeHelper->isSearch();
	}

}