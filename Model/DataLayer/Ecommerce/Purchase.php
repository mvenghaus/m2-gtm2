<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\Ecommerce;

use Inkl\GoogleTagManager\Helper\Config\DataLayerEcommerceConfig;
use Inkl\GoogleTagManager\Helper\RouteHelper;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;

class Purchase
{
    /** @var DataLayerEcommerceConfig */
    private $dataLayerEcommerceConfig;
    /** @var RouteHelper */
    private $routeHelper;
    /** @var StoreManagerInterface */
    private $storeManager;
    /** @var Session */
    private $session;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerEcommerceConfig $dataLayerEcommerceConfig
	 * @param DataLayer $dataLayer
	 * @param RouteHelper $routeHelper
	 * @param StoreManagerInterface $storeManager
	 * @param Session $session
	 */
    public function __construct(DataLayerEcommerceConfig $dataLayerEcommerceConfig,
                                DataLayer $dataLayer,
                                RouteHelper $routeHelper,
                                StoreManagerInterface $storeManager,
                                Session $session)
    {

        $this->dataLayerEcommerceConfig = $dataLayerEcommerceConfig;
        $this->routeHelper = $routeHelper;
        $this->storeManager = $storeManager;
        $this->session = $session;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $order = $this->session->getLastRealOrder();

        if (!$order)
        {
            return;
        }

        $ecommerce = [
            'purchase' => [
                'actionField' => $this->getActionField($order),
                'products' => $this->getProducts($order)
            ]
        ];

        $this->dataLayer->add('ecommerce', $ecommerce, 'ecommerce_purchase');
    }

    protected function getActionField(\Magento\Sales\Model\Order $order)
    {
        return [
            'id' => $order->getIncrementId(),
            'affiliation' => 'Online Shop',
            'revenue' => round($order->getSubtotal(), 2),
            'tax' => round($order->getTaxAmount(), 2),
            'shipping' => round($order->getShippingAmount(), 2),
            'coupon' => (string)$order->getCouponCode(),
	        'email' => $order->getCustomerEmail()
        ];
    }

    protected function getProducts(\Magento\Sales\Model\Order $order)
    {
        $products = [];
        foreach ($order->getAllVisibleItems() as $orderItem)
        {
            $products[] = [
                'id' => $orderItem->getSku(),
                'name' => $orderItem->getName(),
                'price' => round($orderItem->getPrice(), 2),
                'quantity' => (int)$orderItem->getQtyOrdered()
            ];
        }

        return $products;
    }

    protected function isEnabled()
    {
        return $this->dataLayerEcommerceConfig->isPurchaseEnabled() && $this->routeHelper->isPurchase();
    }

}
