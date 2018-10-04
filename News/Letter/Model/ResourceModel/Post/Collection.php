<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Model\ResourceModel\Post;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'newsletter_id';
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(
            'News\Letter\Model\Post',
            'News\Letter\Model\ResourceModel\Post'
        );
    }
}