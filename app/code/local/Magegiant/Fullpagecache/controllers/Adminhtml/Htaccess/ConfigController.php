<?php

class Magegiant_Fullpagecache_Adminhtml_Htaccess_ConfigController extends Magegiant_Fullpagecache_Controller_Adminhtml_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('system/fullpagecache');
        $this->_addContent($this->getLayout()->createBlock('fullpagecache/adminhtml_htaccess_config'));
        $this->renderLayout();
    }

    public function saveHtaccessConfigAction()
    {
        $postData = $this->getRequest()->getPost();

        try {
            if (isset($postData['activate']) && isset($postData['activate']['deflate'])) {
                Mage::getModel('fullpagecache/htaccess_config')->activateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_DEFLATE);
            } else {
                Mage::getModel('fullpagecache/htaccess_config')->desactivateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_DEFLATE);
            }

            if (isset($postData['activate']) && isset($postData['activate']['expire'])) {
                Mage::getModel('fullpagecache/htaccess_config')->desactivateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_EXPIRE);
                Mage::getModel('fullpagecache/htaccess_config')->activateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_EXPIRE, $postData['time_expire_configs']);
            } else {
                Mage::getModel('fullpagecache/htaccess_config')->desactivateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_EXPIRE);
            }

            if (isset($postData['activate']) && isset($postData['activate']['etags'])) {
                Mage::getModel('fullpagecache/htaccess_config')->activateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_ETAGS);
            } else {
                Mage::getModel('fullpagecache/htaccess_config')->desactivateHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_ETAGS);
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Htaccess Config successfully saved !!'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/index');
    }
}