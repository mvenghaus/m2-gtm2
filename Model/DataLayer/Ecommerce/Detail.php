<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Ecommerce;

use Inkl\GoogleTagManager\Helper\Config\DataLayerEcommerceConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Framework\Registry;

class Detail
{
	/** @var DataLayerEcommerceConfig */
	private $dataLayerEcommerceConfig;
	/** @var RouteHelper */
	private $routeHelper;
	/** @var Registry */
	private $registry;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerEcommerceConfig $dataLayerEcommerceConfig
	 * @param RouteHelper $routeHelper
	 * @param DataLayer $dataLayer
	 * @param Registry $registry
	 */
	public function __construct(DataLayerEcommerceConfig $dataLayerEcommerceConfig,
	                            RouteHelper $routeHelper,
	                            DataLayer $dataLayer,
	                            Registry $registry)
	{
		$this->dataLayerEcommerceConfig = $dataLayerEcommerceConfig;
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

		$product = $this->registry->registry('current_product');
		if (!$product)
		{
			return;
		}

		$ecommerce = [
			'detail' => [
				'actionField' => [],
				'products' => [[
					'id' => $product->getSku(),
					'name' => $product->getName(),
					'price' => round($product->getPriceInfo()->getPrice(\Magento\Catalog\Pricing\Price\FinalPrice::PRICE_CODE)->getAmount()->getBaseAmount(), 2),
				]]
			]
		];

		$this->dataLayer->add('ecommerce', $ecommerce, 'ecommerce_detail');
	}

	private function isEnabled()
	{
		return $this->dataLayerEcommerceConfig->isDetailEnabled() && $this->routeHelper->isProduct();
	}

}
