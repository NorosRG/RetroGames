<?php

class Magegiant_Fullpagecache_Helper_Cache_Blockhtml_Generic_Dynamic_Impl extends Magegiant_Fullpagecache_Helper_Cache_Blockhtml_Abstract
{
    public function buildCacheKey($block)
    {
        return parent::buildFullBaseCacheKey($block) . Magegiant_Fullpagecache_Helper_Data::formatCacheKey(Magegiant_Fullpagecache_Helper_Url::getCurrentUrl(Magegiant_Fullpagecache_Helper_Data::getQueryStringFilters()));
    }

    public function buildCacheTags($block)
    {
        return parent::buildBaseCacheTags($block);
    }
}