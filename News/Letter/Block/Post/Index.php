<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace Codilar\Marketplace\Block\Vendors;


use Magento\Framework\View\Element\Template;

class Index extends Template
{
    protected $_postFactory;


    public function __construct(\Magento\Framework\View\Element\Template\Context $context,
                                \News\Letter\Model\PostFactory $postFactory
)
    {
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    public function getVendors()
    {
       $vendors = $this->_postFactory->create();
          return ($vendors->getCollection()->getData());
    }
}