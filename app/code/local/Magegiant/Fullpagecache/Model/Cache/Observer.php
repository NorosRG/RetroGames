<?php

class Magegiant_Fullpagecache_Model_Cache_Observer
{
    public function cleanFullpagecacheCache($observer)
    {
        if (in_array(Mage::app()->getRequest()->getParam('section'), array('general', 'web', 'design', 'currency', 'fullpagecache'))) {
            Mage::app()->cleanCache(
                Magegiant_Fullpagecache_Helper_Data::CACHE_FULLPAGE_OBJECTS,
                Magegiant_Fullpagecache_Helper_Data::CACHE_BLOCKHTML_OBJECTS,
                Mage_Core_Model_Store::CACHE_TAG,
                Mage_Cms_Model_Block::CACHE_TAG
            );
        }
    }

    public function cleanOldCacheEntries()
    {
        Mage::app()->getCache()->clean(Zend_Cache::CLEANING_MODE_OLD);
    }

    public function cleanCmsPageCache($observer)
    {
        if (version_compare(Mage::getVersion(), '1.4.0', '<')) {
            Mage::app()->cleanCache(array('cms_page'));
        }
    }

    public function cleanCategoryCache($observer)
    {
        if (version_compare(Mage::getVersion(), '1.4.0', '<')) {
            $category = $observer->getEvent()->getDataObject();
            Mage::app()->cleanCache(Mage_Catalog_Model_Category::CACHE_TAG . "_" . $category->getId());
        }
    }

    public function cleanProductCategoriesCache($observer)
    {
        if (version_compare(Mage::getVersion(), '1.4.0', '<')) {
            $product = $observer->getEvent()->getDataObject();
            $this->_cleanProductsCategoriesCache(array($product->getId()));
        }
    }

    public function cleanStockItemCache($observer)
    {
        $item = $observer->getEvent()->getItem();

        $currentContext = new Varien_Object(array('item' => $item, 'can_clean_stock_item_cache' => true));
        Magegiant_Fullpagecache_Main::getInstance()->dispatchEvent('fullpagecache_before_clean_stock_item_cache', array('data_object' => $currentContext));

        if (!$currentContext->getCanCleanStockItemCache() || Mage::registry('disable_clean_stock_item_cache') === true) {
            return;
        }

        if ($item->getStockStatusChangedAutomaticallyFlag() || $item->dataHasChangedFor('is_in_stock')) {
            $this->_cleanProductsCategoriesCache(array($item->getProductId()));
        }
    }

    public function cleanOrderItemsCache($observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (Mage::getStoreConfig('fullpagecache/cache_fullpage/clean_cache_each_order')) {
            $productsIds = array();
            foreach ($order->getAllItems() as $item) {
                $productsIds[] = $item->getProductId();
            }

            $this->_cleanProductsCategoriesCache($productsIds);
        }
    }

    protected function _cleanProductsCategoriesCache($productsIds)
    {
        if (empty($productsIds)) {
            return;
        }

        $cacheTagsToDelete = array();
        foreach ($productsIds as $productId) {
            $cacheTagsToDelete[] = Mage_Catalog_Model_Product::CACHE_TAG . "_" . $productId;
        }

        $resource = Mage::getSingleton('core/resource');
        $read = $resource->getConnection('core_read');
        $select = $read->select()->from($resource->getTableName('catalog_category_product'), 'category_id')->where('product_id IN (?)', $productsIds)->group('category_id');

        $categoriesIds = $read->fetchCol($select);
        foreach ($categoriesIds as $categoryId) {
            $cacheTagsToDelete[] = Mage_Catalog_Model_Category::CACHE_TAG . "_" . $categoryId;
        }

        Mage::app()->cleanCache($cacheTagsToDelete);
    }
}