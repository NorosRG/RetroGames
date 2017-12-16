<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Fullpage_Cleaner extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate("magegiant/fullpagecache/cache/fullpage/cleaner.phtml");
    }

    public function getCleanCacheFullpageObjectsUrl()
    {
        return $this->getUrl('*/*/cleanCacheFullpageObjects');
    }
}