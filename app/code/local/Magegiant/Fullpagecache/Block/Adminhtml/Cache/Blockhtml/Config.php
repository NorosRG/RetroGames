<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Blockhtml_Config extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_cache_blockhtml_config';
        $this->_blockGroup = 'fullpagecache';
        $this->_headerText = Mage::helper('fullpagecache')->__('Cache Block Config');
        $this->_addButtonLabel = Mage::helper('fullpagecache')->__('Add Config');
        parent::__construct();
    }
}