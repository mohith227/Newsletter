<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Block\Dashboard;

class Grids extends \Magento\Backend\Block\Dashboard\Grids{

    protected function _prepareLayout()
    {
        $this->addTab(
            'newsletter_count',
            [
                'label' => __('Newsletter Count'),
                'url' => $this->getUrl('newsletter/*/newslettercount', ['_current' => true]),
                'class' => 'ajax'
            ]
        );


        return parent::_prepareLayout();
    }
}