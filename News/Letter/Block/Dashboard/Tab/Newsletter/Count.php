<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Block\Dashboard\Tab\Newsletter;


class Count extends \Magento\Backend\Block\Dashboard\Grid
{

    /**
     * @var string
     */
    protected $_template = 'Magento_Backend::dashboard/grid.phtml';

    /**
     * @var \News\Letter\Model\ResourceModel\Post\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Count constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \News\Letter\Model\ResourceModel\Post\CollectionFactory $collectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \News\Letter\Model\ResourceModel\Post\CollectionFactory $collectionFactory,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('newslettercountGrid');
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareCollection()
    {
        $collection = $this->_collectionFactory->create()
                                    ->addFieldToFilter('newsletter_id',1);
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * {@inheritdoc}
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'subscribe_count',
            [
                'header' => __('subscribe'),
                'sortable' => false,
                'index' => 'subscribe_count',
                'type' => 'number',
                'header_css_class' => 'col-orders',
                'column_css_class' => 'col-orders'
            ]
        );
        $this->addColumn(
            'cancel_count',
            [
                'header' => __('cancel'),
                'sortable' => false,
                'index' => 'cancle_count',
                'type' => 'number',
                'header_css_class' => 'col-orders',
                'column_css_class' => 'col-orders'
            ]
        );
        
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        
        return parent::_prepareColumns();
    }

}
