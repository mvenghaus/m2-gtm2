<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Catalog;

use Inkl\GoogleTagManager\Helper\Config\DataLayerCatalogConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Framework\View\LayoutInterface;

class SearchProducts
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

        $searchProducts = $this->getSearchProducts();

        $this->dataLayer->add('searchProducts', $searchProducts);
    }

    private function getSearchProducts()
    {
        $searchProductListBlock = $this->layout->getBlock('search_result_list');
        if (!$searchProductListBlock) return [];

        $searchProducts = [];
        foreach ($searchProductListBlock->getLoadedProductCollection() as $product)
        {
            $searchProducts[] = [
                'id' => $product->getSku(),
                'name' => $product->getName(),
                'price' => round($product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE)->getAmount()->getBaseAmount(), 2),
            ];
        }

        return $searchProducts;
    }

    private function isEnabled()
    {
        return $this->dataLayerCatalogConfig->isSearchProductsEnabled() && $this->routeHelper->isSearch();
    }

}