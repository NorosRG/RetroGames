<?php

class Magegiant_Fullpagecache_Model_Cache_Fullpage_Observer extends Varien_Object
{
    // Retro compat with 1.2.6 without config clean cache, will be removed in 1.2.8
    public function decidePutPageInCache($observer)
    {
        $this->handleFullpageCache($observer);
    }

    public function handleFullpageCache($observer)
    {
        $response = Mage::app()->getResponse();

        if ($response->getHttpResponseCode() != '200'
            || !Mage::helper("fullpagecache")->isCacheFullpageEnabled()
            || !Mage::getSingleton('fullpagecache/cache_fullpage_cookie')->getFullpagecacheCacheFullpage()
        ) {
            return;
        }

        $cacheFullpageConfig = Mage::getSingleton('fullpagecache/cache_fullpage_config');

        if ($cacheFullpageConfig->tryPageMatchWithCacheFullpageConfig()) {
            if ($cacheHelper = Mage::helper($cacheFullpageConfig->getHelperClass())) {
                $cacheHelper->handleFullpageCache($response, $cacheFullpageConfig->getCacheLifetime());
            }
        }
    }
}