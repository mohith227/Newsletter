<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Api\Data;
interface PostInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case.
     */
    const NEWSLETTER_ID = 'newsletter_id';

    /**
     * @return int
     */
    public function getNewsletterId();

    /**
     * @param $newsletterId
     * @return int
     */
    public function setNewsletterId($newsletterId);
    
    
  
}