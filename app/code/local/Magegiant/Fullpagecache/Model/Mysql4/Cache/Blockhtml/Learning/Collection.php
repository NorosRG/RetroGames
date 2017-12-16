<?php

class Magegiant_Fullpagecache_Model_Mysql4_Cache_Blockhtml_Learning_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('fullpagecache/cache_blockhtml_learning');
    }

    public function addLearningFieldsToFilter($parent)
    {
        return $this
            ->addFieldToFilter('store_id', $parent->getStoreId())
            ->addFieldToFilter('block_class', $parent->getBlockClass())
            ->addFieldToFilter('block_template', $parent->getBlockTemplate());
    }
}