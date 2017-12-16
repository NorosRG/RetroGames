<?php

class Magegiant_Fullpagecache_Helper_Cache_Fullpage_Generic_Impl extends Magegiant_Fullpagecache_Helper_Cache_Fullpage_Abstract
{
    public function buildCacheTags()
    {
        return parent::buildBaseCacheTags();
    }

    public function buildCacheKey($storeCode = null, $currencyCode = null, $defaultCurrencyCode = null)
    {
        return parent::buildCacheKey($storeCode, $currencyCode, $defaultCurrencyCode);
    }
}