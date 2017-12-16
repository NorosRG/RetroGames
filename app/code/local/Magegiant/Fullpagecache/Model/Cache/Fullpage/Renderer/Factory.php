<?php

class Magegiant_Fullpagecache_Model_Cache_Fullpage_Renderer_Factory
{
    private static $_instance;
    protected $_store;

    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (!self::$_instance) {
            $instanceName = Magegiant_Fullpagecache_Main::getInstanceName(__CLASS__);
            self::$_instance = new $instanceName();
        }

        return self::$_instance;
    }

    public function buildRenderer()
    {
        if (!Mage::isInstalled()
            || !$this->_isFullpagecacheEnabled()
            || $this->_isPageInExcludedPathInfo()
            || !$this->_canUseFullpagecacheFullPageCache()
            || !$this->_retrieveStore()
            || !$this->_checkStore()
        ) {
            return new Magegiant_Fullpagecache_Model_Cache_Fullpage_Renderer_Void();
        }

        $currentContext = new Varien_Object(array('store' => $this->_store, 'request' => Magegiant_Fullpagecache_Helper_Url::getRequest()));
        Magegiant_Fullpagecache_Main::getInstance()->dispatchEvent('fullpagecache_before_build_renderer', array('data_object' => $currentContext));

        if ($currentContext->getDisableRenderer() == true) {
            return new Magegiant_Fullpagecache_Model_Cache_Fullpage_Renderer_Void();
        }

        return new Magegiant_Fullpagecache_Model_Cache_Fullpage_Renderer_Standard($this->_store);
    }

    protected function _isFullpagecacheEnabled()
    {
        $fullpagecacheModuleFile = BP . DS . 'app' . DS . 'etc' . DS . 'modules' . DS . 'Magegiant_Fullpagecache.xml';

        return file_exists($fullpagecacheModuleFile) && strpos(file_get_contents($fullpagecacheModuleFile), 'true') !== false;
    }

    protected function _retrieveStore()
    {
        $currentUrl = Magegiant_Fullpagecache_Helper_Url::getCurrentUrl();
        $storemap = new Magegiant_Fullpagecache_Model_Core_Storemap(true);
        $stores = $storemap->load();
        $matchingStores = array();

        // Return false if storemap doesn t exists or force changing storeview
        if (!is_array($stores) || strpos(Magegiant_Fullpagecache_Helper_Url::getQueryString(), '___store') !== false) {
            return false;
        }

        foreach ($stores as $store) {
            if (strpos($currentUrl, $store['secure_url']) !== false
                || strpos($currentUrl, $store['unsecure_url']) !== false
            ) {
                // In Not first Request case, we have to check that the user
                // didn't change storeview
                if (!isset($_COOKIE) || !isset($_COOKIE['fullpagecache_last_store']) || $_COOKIE['fullpagecache_last_store'] == $store['code']) {
                    $matchingStores[] = $store;
                }
            }
        }

        if (count($matchingStores) == 1) {
            $this->_store = $matchingStores[0];

            return true;
        } // In case several storeview url matching, we have to select default one
        elseif (count($matchingStores) > 1) {
            foreach ($matchingStores as $matchingStore) {
                if ($matchingStore['is_default']) {
                    $this->_store = $matchingStore;

                    return true;
                }
            }
        } else {
            return false;
        }
    }

    protected function _checkStore()
    {
        $storeCode = (is_array($this->_store) && isset($this->_store['code'])) ? $this->_store['code'] : '';
        $defaultCurrencyCode = (is_array($this->_store) && isset($this->_store['default_currency_code'])) ? $this->_store['default_currency_code'] : '';

        return is_array($this->_store) && !empty($storeCode) && !empty($defaultCurrencyCode);
    }

    protected function _isPageInExcludedPathInfo()
    {
        $simpleConfig = Magegiant_Fullpagecache_Helper_Data::getSimpleConfig();
        $cacheFullpageExcludes = array($simpleConfig->getNode('admin/routers/adminhtml/args/frontName')->asArray());

        if ($simpleConfig->getNode('global/fullpagecache/cache_fullpage/pathinfo_excludes')) {
            $pathinfoExcludes = $simpleConfig->getNode('global/fullpagecache/cache_fullpage/pathinfo_excludes')->asArray();

            if (!empty($pathinfoExcludes)) {
                $cacheFullpageExcludes = array_merge($cacheFullpageExcludes, explode(',', $pathinfoExcludes));
            }
        }

        foreach ($cacheFullpageExcludes as $cacheFullpageExclude) {
            if (strpos(Magegiant_Fullpagecache_Helper_Url::getPathInfo(), '/' . $cacheFullpageExclude) === 0) {
                return true;
            }
        }

        return false;
    }

    protected function _canUseFullpagecacheFullPageCache()
    {
        if (Magegiant_Fullpagecache_Helper_Url::getRequest()->getParam(Magegiant_Fullpagecache_Helper_Data::FULLPAGECACHE_REFRESH_CACHE_ON_PAGE)) {
            return false;
        }

        if (!isset($_COOKIE) || !isset($_COOKIE['fullpagecache_cache_fullpage']) || $_COOKIE['fullpagecache_cache_fullpage'] == 1) {
            return true;
        }

        return false;
    }
}