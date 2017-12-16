<?php

class Magegiant_Fullpagecache_Model_Mysql4_Cache_Fullpage_Config extends Magegiant_Fullpagecache_Model_Mysql4_Cache_Config
{
    public function _construct()
    {
        $this->_init('fullpagecache/cache_fullpage_config', 'config_id');
    }
}