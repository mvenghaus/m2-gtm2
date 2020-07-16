<?php

namespace Inkl\GoogleTagManager\Block;

use Inkl\GoogleTagManager\Helper\Config\GeneralConfig;
use Magento\Framework\View\Element\Template;

class Tag extends Template
{
	/** @var GeneralConfig */
	private $generalConfig;

	/**
	 * @param Template\Context $context
	 * @param GeneralConfig $generalConfig
	 * @param array $data
	 */
	public function __construct(Template\Context $context,
	                            GeneralConfig $generalConfig,
	                            array $data = [])
	{
		parent::__construct($context, $data);

		$this->generalConfig = $generalConfig;
	}

	protected function _toHtml()
	{
		return 'bla';
	}

}