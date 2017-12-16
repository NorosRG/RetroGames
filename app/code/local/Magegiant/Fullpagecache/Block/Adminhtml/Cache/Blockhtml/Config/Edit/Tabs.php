<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Blockhtml_Config_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    public function __construct()
    {
        parent::__construct();
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('fullpagecache')->__('Cache Block Config'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab('form_section', array(
            'label' => Mage::helper('fullpagecache')->__('General Config'),
            'title' => Mage::helper('fullpagecache')->__('General Config'),
            'content' => $this->getLayout()->createBlock('fullpagecache/adminhtml_cache_blockhtml_config_edit_tab_form')->toHtml(),
        ));

        return parent::_beforeToHtml();
    }
}