<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Catalog;

use Inkl\GoogleTagManager\Helper\Config\DataLayerCatalogConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Catalog\Api\Data\CategoryInterface;
use Magento\Framework\Registry;

class CategoryName
{
	/** @var DataLayerCatalogConfig */
	private $dataLayerCatalogConfig;
	/** @var RouteHelper */
	private $routeHelper;
	/** @var Registry */
	private $registry;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerCatalogConfig $dataLayerCatalogConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 * @param Registry $registry
	 */
	public function __construct(DataLayerCatalogConfig $dataLayerCatalogConfig,
	                            DataLayer $dataLayer,
	                            RouteHelper $routeHelper,
	                            Registry $registry)
	{
		$this->dataLayerCatalogConfig = $dataLayerCatalogConfig;
		$this->routeHelper = $routeHelper;
		$this->registry = $registry;
		$this->dataLayer = $dataLayer;
	}

	public function handle()
	{
		if (!$this->isEnabled())
		{
			return;
		}

		$this->dataLayer->add('categoryName', $this->getCategoryName());
	}

	private function getCategoryName()
	{
		/** @var CategoryInterface $category */
		$category = $this->registry->registry('current_category');
		if (!$category)
		{
			return '';
		}

		return $category->getName();
	}

	private function isEnabled()
	{
		return $this->dataLayerCatalogConfig->isCategoryNameEnabled() && $this->routeHelper->isCategory();
	}

}