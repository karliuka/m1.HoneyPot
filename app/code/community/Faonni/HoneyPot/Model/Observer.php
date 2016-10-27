<?php
/**
 * Faonni
 *  
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade module to newer
 * versions in the future.
 * 
 * @package     Faonni_HoneyPot
 * @copyright   Copyright (c) 2015 Karliuka Vitalii(karliuka.vitalii@gmail.com) 
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Faonni_HoneyPot_Model_Observer
{
    /**
     * Login customer
	 *
     * @param object $observer The Observer object
     * @return object Faonni_HoneyPot_Model_Observer
     */	
    public function login(Varien_Event_Observer $observer) 
	{
		$this->checkHoneyPot($observer, 'login');
		return $this;
    }
	
    /**
     * Create customer
	 *
     * @param object $observer The Observer object
     * @return object Faonni_HoneyPot_Model_Observer
     */	
    public function create(Varien_Event_Observer $observer) 
	{
		$this->checkHoneyPot($observer, 'create');
		return $this;
    }
	
    /**
     * Forgot password
	 *
     * @param object $observer The Observer object.
     * @return object Faonni_HoneyPot_Model_Observer
     */	
    public function forgotpassword(Varien_Event_Observer $observer) 
	{
		$this->checkHoneyPot($observer, 'forgotpassword');
		return $this;
    }
	
    /**
     * Check HoneyPot Captcha
	 *
     * @param object $observer The Observer object	 
     * @param string $actionName
     * @return object Faonni_HoneyPot_Model_Observer
     */	
	public function checkHoneyPot(Varien_Event_Observer $observer, $actionName)
    {
		/** @var $action Mage_Core_Controller_Varien_Action */
		$action = $observer->getControllerAction();
		$request = $action->getRequest();
		$honeypot = $request->getPost('honeypot');
		
		if(empty($honeypot) || !$this->isCorrect($honeypot)) {
			Mage::getSingleton('customer/session')->addError(Mage::helper('faonni_honeypot')->__('Incorrect Request.'));
			
			$action->setFlag('', Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH, true);
			$action->getResponse()->setRedirect(Mage::getUrl('*/*/' . $actionName));					
		}
		return $this;
    }	
	
    /**
     * Check Correct HoneyPot Captcha
	 *
     * @param string $honeypot
     * @return bool
     */	
	public function isCorrect($honeypot)
    {
		return ((time() - $honeypot) > 7);
    }	
}