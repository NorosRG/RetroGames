<?php

class Boutique1_FullpagecacheExt_Helper_Cache_Fullpage_Cms_Page_View_ImplEx extends Magegiant_Fullpagecache_Helper_Cache_Fullpage_Cms_Page_View_Impl
{
    public function isPageCachable()
    {
        $request = Mage::app()->getFrontController()->getRequest();

        return !preg_match('/mapagecms1/', $request->getPathInfo());
    }
}