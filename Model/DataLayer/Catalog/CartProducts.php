<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Catalog;

use Inkl\GoogleTagManager\Helper\Config\DataLayerCatalogConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Checkout\Model\Session;

class CartProducts
{
    /** @var DataLayerCatalogConfig */
    private $dataLayerCatalogConfig;
    /** @var RouteHelper */
    private $routeHelper;
    /** @var Session */
    private $session;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerCatalogConfig $dataLayerCatalogConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 * @param Session $session
	 */
    public function __construct(DataLayerCatalogConfig $dataLayerCatalogConfig,
                                DataLayer $dataLayer,
                                RouteHelper $routeHelper,
                                Session $session
    )
    {
        $this->dataLayerCatalogConfig = $dataLayerCatalogConfig;
        $this->routeHelper = $routeHelper;
        $this->session = $session;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $cartProducts = $this->getCartProducts();

        $this->dataLayer->add('cartProducts', $cartProducts);
    }

    private function getCartProducts()
    {
        $cartProducts = [];

        foreach ($this->session->getQuote()->getAllVisibleItems() as $quoteItem)
        {
            $sku = $quoteItem->getSku();

            $cartProductData = [
                'id' => $sku,
                'name' => $quoteItem->getName(),
                'price' => round($quoteItem->getPrice(), 2),
                'quantity' => 0
            ];

            if (!isset($cartProducts[$sku]))
            {
                $cartProducts[$sku] = $cartProductData;
            }

            $cartProducts[$sku]['quantity'] += $quoteItem->getQty();
        }

        return array_values($cartProducts);
    }

    private function isEnabled()
    {
        return $this->dataLayerCatalogConfig->isCartProductsEnabled() && $this->routeHelper->isCart();
    }

}