<?php

class Magegiant_Fullpagecache_Model_Core_Cache_Container_V14 extends Magegiant_Fullpagecache_Model_Core_Cache_Container_Abstract
{
    public function getCache()
    {
        if (!$this->_cache) {
            $options = $this->_getSimpleConfig()->getNode('global/cache');

            if ($options) {
                $options = $options->asArray();
            } else {
                $options = array();
            }

            $cacheInstance = new Magegiant_Fullpagecache_Model_Core_Cache($options);
            $this->_cache = $cacheInstance->getFrontend();
            $this->_useTwoLevels = $cacheInstance->getUseTwoLevels();
        }

        return $this->_cache;
    }
}