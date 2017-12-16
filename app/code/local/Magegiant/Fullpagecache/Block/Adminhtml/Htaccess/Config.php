<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Htaccess_Config extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("magegiant/fullpagecache/htaccess/config.phtml");
    }

    public function getSaveHtaccessConfigUrl()
    {
        return $this->getUrl('*/*/saveHtaccessConfig');
    }

    public function isActivatedHtaccessConfig($config)
    {
        switch ($config) {
            case ('expire'):
                return Mage::getModel('fullpagecache/htaccess_config')->isActivatedHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_EXPIRE);
            case ('deflate'):
                return Mage::getModel('fullpagecache/htaccess_config')->isActivatedHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_DEFLATE);
            case ('etags'):
                return Mage::getModel('fullpagecache/htaccess_config')->isActivatedHtaccessConfig(Magegiant_Fullpagecache_Model_Htaccess_Config::BEGIN_FULLPAGECACHE_ETAGS);
            default:
                return false;
        }
    }

    protected function _prepareLayout()
    {
        $this->setChild('config_time_expire',
            $this->getLayout()->createBlock('fullpagecache/adminhtml_htaccess_config_time_expire')
        );

        return parent::_prepareLayout();
    }
}