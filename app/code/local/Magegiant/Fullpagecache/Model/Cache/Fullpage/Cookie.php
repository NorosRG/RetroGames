<?php

class Magegiant_Fullpagecache_Model_Cache_Fullpage_Cookie extends Varien_Object
{
    public function getFullpagecacheCacheFullpage($reload = false)
    {
        if ($reload || !$this->hasData('fullpagecache_cache_fullpage')) {
            $this->setData('fullpagecache_cache_fullpage', Mage::helper("fullpagecache")->isCacheFullpageEnabled()
                && Mage::getSingleton('core/session')->getMessages()->count() == 0
                && Mage::getSingleton('catalog/session')->getMessages()->count() == 0
                && Mage::getSingleton('review/session')->getMessages()->count() == 0
                && Mage::getSingleton('customer/session')->getMessages()->count() == 0
                && Mage::getSingleton('checkout/session')->getMessages()->count() == 0
                && !Mage::getSingleton('customer/session')->isLoggedIn()
                && Mage::getSingleton('checkout/cart')->getItemsCount() == 0
                && Mage::helper('catalog/product_compare')->getItemCount() == 0
            );
        }

        return (int)$this->getData('fullpagecache_cache_fullpage');
    }

    public function setFullpagecacheCacheFullpage()
    {
        $this->getFullpagecacheCacheFullpage(true);
    }

    public function sendCookie()
    {
        $cookie = Mage::getModel('core/cookie');
        $cookie->set('fullpagecache_cache_fullpage', $this->getFullpagecacheCacheFullpage(true));
        $cookie->set('fullpagecache_last_store', Mage::app()->getStore()->getCode());
    }
}