<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Fullpage_Config_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'config_id';
        $this->_blockGroup = 'fullpagecache';
        $this->_controller = 'adminhtml_cache_fullpage_config';
        $this->_headerText = '';
        parent::__construct();
        $this->_updateButton('save', 'label', Mage::helper('fullpagecache')->__('Save Config'));
        $this->_updateButton('delete', 'label', Mage::helper('fullpagecache')->__('Delete Config'));
    }
}