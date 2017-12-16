<?php

class Magegiant_Fullpagecache_Adminhtml_Cache_Fullpage_ConfigController extends Magegiant_Fullpagecache_Controller_Adminhtml_Action
{
    public function indexAction()
    {
        if (!Mage::helper('fullpagecache')->isCacheFullpageEnabled()) {
            Mage::getSingleton('adminhtml/session')->addNotice(Mage::helper('fullpagecache')->__('
                Beware, this config needs Full Page Cache activated here :
                <a href="' . $this->getUrl('*/cache/index') . '">Activate Full Page Cache</a> to work'));
        }

        $this->loadLayout();
        $this->_setActiveMenu('system/fullpagecache');
        $this->_addContent($this->getLayout()->createBlock('fullpagecache/adminhtml_cache_fullpage_config'));
        $this->_addContent($this->getLayout()->createBlock('fullpagecache/adminhtml_cache_fullpage_cleaner'));
        $this->renderLayout();
    }

    public function editAction()
    {
        $cacheFullpageConfigId = $this->getRequest()->getParam('config_id');
        $cacheFullpageConfig = Mage::getModel('fullpagecache/cache_fullpage_config')->load($cacheFullpageConfigId);

        if ($cacheFullpageConfig->getId() || $cacheFullpageConfigId == 0) {
            Mage::register('cache_fullpage_config', $cacheFullpageConfig);
            $this->loadLayout();
            $this->_setActiveMenu('system/cache');
            $this->_addContent($this->getLayout()->createBlock('fullpagecache/adminhtml_cache_fullpage_config_edit'))
                ->_addLeft($this->getLayout()->createBlock('fullpagecache/adminhtml_cache_fullpage_config_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('fullpagecache')->__('Item does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function saveAction()
    {
        try {
            $postData = $this->getRequest()->getPost();

            Mage::getModel('fullpagecache/cache_fullpage_config')->setId($this->getRequest()->getParam('config_id'))
                ->setNonNullDatas($postData)
                ->setCacheLifetime($postData['cache_lifetime'])
                ->setActivated(isset($postData['activated']))
                ->save();

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Item was successfully saved'));

            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/edit', array('config_id' => $this->getRequest()->getParam('config_id')));
        }
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function deleteAction()
    {
        if ($this->getRequest()->getParam('config_id') > 0) {
            try {
                Mage::getModel('fullpagecache/cache_fullpage_config')->setId($this->getRequest()->getParam('config_id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Item was successfully deleted'));
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('config_id' => $this->getRequest()->getParam('config_id')));
            }
        }
    }

    public function cleanCacheFullpageObjectsAction()
    {
        try {
            Mage::app()->cleanCache(Magegiant_Fullpagecache_Helper_Data::CACHE_FULLPAGE_OBJECTS);
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Cache Fullpage Objects were successfully deleted'));
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

        $this->_redirect('*/*/');
    }

    public function massEnableAction()
    {
        try {
            $postData = $this->getRequest()->getPost();

            foreach ($postData["config_ids"] as $cacheFullpageConfigId) {
                Mage::getModel('fullpagecache/cache_fullpage_config')->load($cacheFullpageConfigId)->setActivated(1)->save();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Cache Fullpage was successfully enabled'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/', $this->getRequest()->getPost());
        }
    }

    public function massDisableAction()
    {
        try {
            $postData = $this->getRequest()->getPost();

            foreach ($postData["config_ids"] as $cacheFullpageConfigId) {
                $cacheFullpageConfig = Mage::getModel('fullpagecache/cache_fullpage_config');
                $cacheFullpageConfig->load($cacheFullpageConfigId)->setActivated(0)->save();
                Mage::app()->cleanCache($cacheFullpageConfig->getFullActionName());
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Cache Fullpage was successfully disabled'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/', $this->getRequest()->getPost());
        }
    }

    public function massDeleteAction()
    {
        try {
            $postData = $this->getRequest()->getPost();

            foreach ($postData["config_ids"] as $cacheFullpageConfigId) {
                $cacheFullpageConfig = Mage::getModel('fullpagecache/cache_fullpage_config');
                $cacheFullpageConfig->load($cacheFullpageConfigId);
                Mage::app()->cleanCache($cacheFullpageConfig->getFullActionName());
                $cacheFullpageConfig->delete();
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Cache Fullpage was successfully deleted'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/', $this->getRequest()->getPost());
        }
    }

    public function massRefreshAction()
    {
        try {
            $postData = $this->getRequest()->getPost();

            foreach ($postData["config_ids"] as $cacheFullpageConfigId) {
                Mage::app()->cleanCache(Mage::getModel('fullpagecache/cache_fullpage_config')->load($cacheFullpageConfigId)->getFullActionName());
            }

            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Cache config Item(s) was successfully refreshed'));
            $this->_redirect('*/*/');
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('*/*/', $this->getRequest()->getPost());
        }
    }

    public function activateIndexphpAction()
    {
        $indexPhpPath = BP . DS . 'index.php';
        $indexPhpContent = file_get_contents($indexPhpPath);

        if (!preg_match('/Magegiant_Fullpagecache_Main/', $indexPhpContent)) {
            $indexPhpContent = str_replace(
                "Mage::run(",
                "(file_exists(BP . DS . 'app' . DS . 'code' . DS . 'local' . DS . 'Magegiant' . DS . 'Fullpagecache' . DS . 'Main.php')) ? Magegiant_Fullpagecache_Main::init()->renderPage() : false;" . PHP_EOL . PHP_EOL . "Mage::run(",
                $indexPhpContent);
            file_put_contents($indexPhpPath, $indexPhpContent);
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('fullpagecache')->__('Index php has been activated !!'));
        $this->_redirect('adminhtml/system_config/edit', array('section' => 'magegiant_fullpagecache'));
    }
}