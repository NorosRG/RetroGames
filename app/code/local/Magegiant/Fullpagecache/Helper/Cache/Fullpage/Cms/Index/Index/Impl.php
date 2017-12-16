<?php

class Magegiant_Fullpagecache_Helper_Cache_Fullpage_Cms_Index_Index_Impl extends Magegiant_Fullpagecache_Helper_Cache_Fullpage_Abstract
{
    public function buildCacheTags()
    {
        return array_merge(array('cms_page'), parent::buildBaseCacheTags());
    }
}