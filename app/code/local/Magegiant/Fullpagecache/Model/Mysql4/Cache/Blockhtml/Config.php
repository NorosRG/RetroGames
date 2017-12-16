<?php

class Magegiant_Fullpagecache_Model_Mysql4_Cache_Blockhtml_Config extends Magegiant_Fullpagecache_Model_Mysql4_Cache_Config
{
    public function _construct()
    {
        $this->_init('fullpagecache/cache_blockhtml_config', 'config_id');
    }
}