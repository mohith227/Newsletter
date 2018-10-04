<?php
/**
 * Codilar Technologies Pvt. Ltd.
 * @category    [CATEGORY NAME]
 * @package    [PACKAGE NAME]
 * @copyright   Copyright (c) 2016 Codilar. (http://www.codilar.com)
 * @purpose     [BRIEF ABOUT THE FILE]
 * @author       Codilar Team
 **/
namespace News\Letter\Controller\Subscriber;

use Magento\Customer\Api\AccountManagementInterface as CustomerAccountManagement;
use Magento\Customer\Model\Url as CustomerUrl;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\App\Action\Context;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\Controller\Result\JsonFactory;
use News\Letter\Model\PostFactory;

class NewAction extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CustomerAccountManagement
     */
    protected $customerAccountManagement;

    /**
     * Customer session
     *
     * @var Session
     */
    protected $customerSession;

    /**
     * Subscriber factory
     *
     * @var SubscriberFactory
     */
    protected $subscriberFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @var JsonFactory
     */
    protected $resultJsonFactory;
    /**
     * Post Factory
     * @var PostFactory
     */
    protected $_postFactory;

    /**
     * Initialize dependencies.
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Session $customerSession
     * @param StoreManagerInterface $storeManager
     * @param CustomerUrl $customerUrl
     * @param CustomerAccountManagement $customerAccountManagement
     * @param JsonFactory $resultJsonFactory
     * @param PostFactory $postFactory
     */
    public function __construct(
        Context $context,
        SubscriberFactory $subscriberFactory,
        Session $customerSession,
        StoreManagerInterface $storeManager,
        CustomerUrl $customerUrl,
        CustomerAccountManagement $customerAccountManagement,
        PostFactory $postFactory,
        JsonFactory $resultJsonFactory
    ) {
        $this->customerAccountManagement = $customerAccountManagement;
        $this->storeManager = $storeManager;
        $this->subscriberFactory = $subscriberFactory;
        $this->customerSession = $customerSession;
        $this->customerUrl = $customerUrl;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_postFactory = $postFactory;
        parent::__construct($context);
    }

    /**
     * Validates that the email address isn't being used by a different account.
     *
     * @param string $email
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function validateEmailAvailable($email)
    {
        $websiteId = $this->storeManager->getStore()->getWebsiteId();
        if ($this->customerSession->getCustomerDataObject()->getEmail() !== $email
            && !$this->customerAccountManagement->isEmailAvailable($email, $websiteId)
        ) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('This email address is already assigned to another user.')
            );
        }
    }

    /**
     * Validates that if the current user is a guest, that they can subscribe to a newsletter.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function validateGuestSubscription()
    {
        if ($this->_objectManager->get('Magento\Framework\App\Config\ScopeConfigInterface')
                ->getValue(
                    \Magento\Newsletter\Model\Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG,
                    \Magento\Store\Model\ScopeInterface::SCOPE_STORE
                ) != 1
            && !$this->customerSession->isLoggedIn()
        ) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __(
                    'Sorry, but the administrator denied subscription for guests. Please <a href="%1">register</a>.',
                    $this->customerUrl->getRegisterUrl()
                )
            );
        }
    }

    /**
     * Validates the format of the email address
     *
     * @param string $email
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    protected function validateEmailFormat($email)
    {
        if (!\Zend_Validate::is($email, 'EmailAddress')) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Please enter a valid email address.'));
        }
    }

    /**
     * New subscription action
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     * @return void
     */
    public function execute()
    {

        $data['error'] = true;
        $data['message'] = __('Please enter your email address.');
        if ($this->getRequest()->getPost('email'))
        {
            if ($this->getRequest()->isAjax()
                && $this->getRequest()->getMethod() == 'POST'
                && $this->getRequest()->isXmlHttpRequest()
                && $this->getRequest()->getPost('email')
            ) {
                $email = (string) $this->getRequest()->getPost('email');
                try {
                    $this->validateEmailFormat($email);
                    $this->validateGuestSubscription();
                    $this->validateEmailAvailable($email);

                    $status = $this->subscriberFactory->create()->subscribe($email);
                    $data['error'] = false;
                    if ($status == \Magento\Newsletter\Model\Subscriber::STATUS_NOT_ACTIVE) {
                        $data['message'] = __('The confirmation request has been sent.');
                    } else {
                        $post = $this->_postFactory->create()
                            ->getCollection()
                            ->addFieldToFilter('newsletter_id',1)
                            ->getFirstItem();
                        $count = $post->getData('subscribe_count');
                        $count = $count+1;
                        $this->_postFactory->create()
                            ->getCollection()
                            ->addFieldToFilter('newsletter_id',1)
                            ->getFirstItem()
                            ->setData('subscribe_count',$count)
                            ->save();
                        $data['message'] = __('Thank you for your subscription.');
                    }
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
                    $data['message'] = __('There was a problem with the subscription: %1', $e->getMessage());
                }
                catch (\Exception $e) {
                    //$data['message'] = __('Something went wrong with the subscription.');
                    $data['message'] = $e->getMessage();
                }           
            }
        }
        else
        {
            if ($this->getRequest()->isAjax()
                && $this->getRequest()->getMethod() == 'POST'
            )
            {
                $post = $this->_postFactory->create()
                    ->getCollection()
                    ->addFieldToFilter('newsletter_id',1)
                    ->getFirstItem();
                $count = $post->getData('cancle_count');
                $count = $count+1;
                $this->_postFactory->create()
                    ->getCollection()
                    ->addFieldToFilter('newsletter_id',1)
                    ->getFirstItem()
                    ->setData('cancle_count',$count)
                    ->save();
                $data['message'] = __('Thank you');
            }
                
            
        }
     
     
        $result = $this->resultJsonFactory->create();
        return $result->setData($data);
    }
}