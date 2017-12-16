<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Blockhtml_Cleaner extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("magegiant/fullpagecache/cache/blockhtml/cleaner.phtml");
    }

    public function getCleanCacheBlockhtmlObjectsUrl()
    {
        return $this->getUrl('*/*/cleanCacheBlockhtmlObjects');
    }
}