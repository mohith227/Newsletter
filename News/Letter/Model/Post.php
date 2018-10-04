<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Model;
use News\Letter\Api\Data\PostInterface;

class Post extends \Magento\Framework\Model\AbstractModel implements PostInterface
{
    const CACHE_TAG = 'newsletter_count';

    protected $_cacheTag = 'newsletter_count';

    protected $_eventPrefix = 'newsletter_count';

    protected function _construct()
    {
        $this->_init('News\Letter\Model\ResourceModel\Post');
    }

    /**
     * @return integer
     */
    public function getNewsletterId()
    {
        return $this->getData(self::NEWSLETTER_ID);
    }

    /**
     * @param $newsletterId
     * @return $this
     */
    public function setNewsletterId($newsletterId)
    {
        return $this->setData(self::NEWSLETTER_ID, $newsletterId);
    }
  
}