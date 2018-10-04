<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Controller\Adminhtml\Dashboard;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\LayoutFactory;

class Newslettercount extends Action
{
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    /**
     * Newslettercount constructor.
     * @param Action\Context $context
     * @param LayoutFactory $layoutFactory
     */
    public function __construct(
        Action\Context $context,
        LayoutFactory $layoutFactory
    )
    {
        parent::__construct($context);
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * Gets latest customers list
     *
     * @return \Magento\Framework\Controller\Result\Raw
     */
    public function execute()
    {
        
        $output = $this->layoutFactory->create()
            ->createBlock(\News\Letter\Block\Dashboard\Tab\Newsletter\Count::class)
            ->toHtml();
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        return $resultRaw->setContents($output);
    }
}