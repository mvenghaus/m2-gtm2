<?php

namespace Inkl\GoogleTagManager\Model\DataLayer\General;

use Inkl\GoogleTagManager\Helper\Config\DataLayerGeneralConfig;
use Inkl\GoogleTagManager\Model\DataLayer;
use Magento\Framework\Locale\ResolverInterface;

class LocaleCode
{
    /** @var DataLayerGeneralConfig */
    private $dataLayerGeneralConfig;
    /** @var ResolverInterface */
    private $resolver;
	/** @var DataLayer */
	private $dataLayer;

	/**
	 * @param DataLayerGeneralConfig $dataLayerGeneralConfig
	 * @param DataLayer $dataLayer
	 * @param ResolverInterface $resolver
	 */
    public function __construct(DataLayerGeneralConfig $dataLayerGeneralConfig,
                                DataLayer $dataLayer,
                                ResolverInterface $resolver
    )
    {

        $this->dataLayerGeneralConfig = $dataLayerGeneralConfig;
        $this->resolver = $resolver;
	    $this->dataLayer = $dataLayer;
    }

    public function handle()
    {
        if (!$this->isEnabled())
        {
            return;
        }

        $this->dataLayer->add('localeCode', $this->resolver->getLocale());
    }

    private function isEnabled()
    {
        return $this->dataLayerGeneralConfig->isLocaleCodeEnabled();
    }
}
