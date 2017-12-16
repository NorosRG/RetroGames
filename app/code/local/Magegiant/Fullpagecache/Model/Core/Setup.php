<?php

class Magegiant_Fullpagecache_Model_Core_Setup extends Mage_Core_Model_Resource_Setup
{
    /**
     * Apply Index module DB updates and sync indexes declaration
     *
     * @return void
     */
    public function applyUpdates()
    {
        parent::applyUpdates();
        $this->_syncIndexes();
    }

    /**
     * Delete catalog_url index if fullpagecache optim index is enabled
     *
     * @return Magegiant_Fullpagecache_Model_Core_Setup
     */
    protected function _syncIndexes()
    {
        // There is no module Mage_Index in <= 1.3.X versions so we must quit cause it s used in further impl.
        if (version_compare(Mage::getVersion(), '1.4.0', '<')) {
            return $this;
        }

        $connection = $this->getConnection();
        if (!$connection) {
            return $this;
        }

        $indexProcessTable = $this->getTable('index/process');
        $select = $connection->select()->from($indexProcessTable, 'indexer_code');
        $existingIndexes = $connection->fetchCol($select);

        // Mage::getStoreConfig seems not to work here.. and i dont investigate more cause it is not important
        // to use it or make a custom query like below.
        $coreConfigDataTable = $this->getTable('core/config_data');
        $select = $connection->select()->from($coreConfigDataTable, 'value')->where('path = ?', 'fullpagecache/optimization_index_url/enable');
        $isOptimizationIndexUrlEnabled = $connection->fetchOne($select);

        if ($isOptimizationIndexUrlEnabled === '1' && in_array('catalog_url', $existingIndexes)) {
            $connection->delete($indexProcessTable, $connection->quoteInto('indexer_code IN (?)', array('catalog_url')));
        } elseif (empty($isOptimizationIndexUrlEnabled) && in_array('fullpagecache_catalog_url', $existingIndexes)) {
            $connection->delete($indexProcessTable, $connection->quoteInto('indexer_code IN (?)', array('fullpagecache_catalog_url')));
        }
    }
}