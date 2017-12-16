<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Blockhtml_Learning extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_cache_blockhtml_learning';
        $this->_blockGroup = 'fullpagecache';
        $this->_headerText = Mage::helper('fullpagecache')->__('Cache Blockhtml Learning');
        parent::__construct();
        $this->removeButton('add');
    }
}