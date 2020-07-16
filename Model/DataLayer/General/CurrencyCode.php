<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\General;

use Inkl\GoogleTagManager\Helper\Config\DataLayerGeneralConfig;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Store\Model\StoreManagerInterface;

class CurrencyCode
{
    /** @var DataLayerGeneralConfig */
    private $dataLayerGeneralConfig;
    /** @var StoreManagerInterface */
    private $storeManager;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerGeneralConfig $dataLayerGeneralConfig
	 * @param DataLayer $dataLayer
	 * @param StoreManagerInterface $storeManager
	 */
    public function __construct(DataLayerGeneralConfig $dataLayerGeneralConfig,
                                DataLayer $dataLayer,
                                StoreManagerInterface $storeManager)
    {
        $this->dataLayerGeneralConfig = $dataLayerGeneralConfig;
        $this->storeManager = $storeManager;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $this->dataLayer->add('currencyCode', $this->storeManager->getStore()->getCurrentCurrency()->getCode());
    }

    private function isEnabled()
    {
        return $this->dataLayerGeneralConfig->isCurrencyCodeEnabled();
    }
}
