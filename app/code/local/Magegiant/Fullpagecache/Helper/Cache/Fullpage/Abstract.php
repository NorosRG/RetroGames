<?php

abstract class Magegiant_Fullpagecache_Helper_Cache_Fullpage_Abstract extends Mage_Core_Helper_Abstract
{
    abstract public function buildCacheTags();

    public function buildBaseCacheTags()
    {
        return array(
            Mage_Catalog_Model_Category::CACHE_TAG,
            Mage_Core_Model_Store::CACHE_TAG,
            Magegiant_Fullpagecache_Helper_Data::FULLPAGE,
            Magegiant_Fullpagecache_Helper_Data::CACHE_FULLPAGE_OBJECTS,
            Mage::app()->getFrontController()->getAction()->getFullActionName()
        );
    }

    public function buildCacheKey($storeCode = null, $currencyCode = null, $defaultCurrencyCode = null)
    {
        $storeCode = (!is_null($storeCode)) ? $storeCode : Mage::app()->getStore()->getCode();
        $currencyCode = (!is_null($currencyCode)) ? $currencyCode : Mage::app()->getStore()->getCurrentCurrencyCode();
        $defaultCurrencyCode = (!is_null($defaultCurrencyCode)) ? $defaultCurrencyCode : Mage::app()->getStore()->getDefaultCurrencyCode();
        $url = Magegiant_Fullpagecache_Helper_Url::getCurrentUrl(Magegiant_Fullpagecache_Helper_Data::getQueryStringFilters());

        $cacheKey = $storeCode . '_' . Magegiant_Fullpagecache_Helper_Data::getDeviceKey() . '_' . $url;
        $cacheKey = ($defaultCurrencyCode == $currencyCode) ? $cacheKey : $currencyCode . '_' . $cacheKey;

        $currentContext = new Varien_Object(array('cache_key' => $cacheKey, 'url' => $url, 'store_code' => $storeCode, 'currency_code' => $currencyCode));
        Magegiant_Fullpagecache_Main::getInstance()->dispatchEvent('fullpagecache_before_build_cache_fullpage_key', array('data_object' => $currentContext));

        return Magegiant_Fullpagecache_Helper_Data::formatCacheKey($currentContext->getCacheKey());
    }

    public function isPageCachable()
    {
        return true;
    }

    public function handleFullpageCache($response, $cacheLifetime)
    {
        if (Mage::app()->getRequest()->getParam(Magegiant_Fullpagecache_Helper_Data::FULLPAGECACHE_REFRESH_CACHE_ON_PAGE)) {
            Mage::app()->cleanCache($this->buildCacheTags());
        }

        if ($this->isPageCachable()) {
            Mage::app()->saveCache(
                $response->getBody(false),
                $this->buildCacheKey(),
                $this->buildCacheTags(),
                $cacheLifetime
            );
        }
    }
}