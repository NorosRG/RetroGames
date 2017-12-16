<?php

class Magegiant_Fullpagecache_Model_Optimization_Observer
{
    public function observeHtml($observer)
    {
        $controllerAction = $observer->getEvent()->getControllerAction();
        $bodyHtml = $controllerAction->getResponse()->getBody();

        $modelCdn = Mage::getModel('fullpagecache/optimization_cdn');

        if (!empty($bodyHtml) && Mage::getStoreConfig('fullpagecache/optimization_cdn/enabled')) {
            $bodyHtml = $modelCdn->cdnHtml($bodyHtml);
        }

        $controllerAction->getResponse()->clearBody();
        $controllerAction->getResponse()->appendBody($bodyHtml);
    }

    public function addSpriteCss($observer)
    {
        $design = Mage::getDesign();

        if (file_exists($design->getSkinBaseDir() . DS . 'css' . DS . 'sprite.css')
            || file_exists($design->getSkinBaseDir(array('_theme' => 'default')) . DS . 'css' . DS . 'sprite.css')
        ) {
            $observer->getEvent()->getLayout()->getUpdate()->addHandle('add_sprite_css');
        }
    }

    public function handleFullpagecacheIndexUrlActivation($observer)
    {
        // There is no module Mage_Index in <= 1.3.X versions so we must quit cause it s used in further impl.
        if (version_compare(Mage::getVersion(), '1.4.0', '<')) {
            return;
        }

        if (Mage::app()->getRequest()->getParam('section') == 'fullpagecache') {
            $processes = Mage::getResourceModel('index/process_collection')->addFieldToFilter('indexer_code',
                array('in' => array('catalog_url', 'fullpagecache_catalog_url'))
            );

            if (Mage::getStoreConfigFlag('fullpagecache/optimization_index_url/enable')) {
                $oldIndexerCode = 'catalog_url';
                $newIndexerCode = 'fullpagecache_catalog_url';
            } else {
                $oldIndexerCode = 'fullpagecache_catalog_url';
                $newIndexerCode = 'catalog_url';
            }

            switch (count($processes)) {
                case 2:
                    $processes->getItemByColumnValue('indexer_code', $oldIndexerCode)->delete();
                    break;
                case 1:
                    $oldProcess = $processes->getItemByColumnValue('indexer_code', $oldIndexerCode);
                    if ($oldProcess) {
                        $oldProcess->setIndexerCode($newIndexerCode)->save();
                    }
                    break;
                default:
                    break;
            }
        }
    }

    public function changeAbsoluteUrlsInMergedCss($observer)
    {
        $block = $observer->getEvent()->getBlock();

        // If one of this conditions is not filled it's not necessary to continue
        if (!($block instanceof Mage_Page_Block_Html_Head)
            || !Mage::getStoreConfig('dev/css/merge_css_files')
            || !Mage::getStoreConfig('fullpagecache/optimization_cdn/enabled')
        ) {
            return;
        }

        $files = array();
        $mergedCssDir = Mage::getBaseDir('media') . DS . 'css';
        $mergedCssDirEntries = scandir($mergedCssDir);

        foreach ($mergedCssDirEntries as $entry) {
            if (!in_array($entry, array('.', '..'))
                && is_file($mergedCssDir . DS . $entry)
            ) {
                $files[] = $mergedCssDir . DS . $entry;
            }
        }

        $alreadyTreatedFiles = unserialize(Mage::app()->loadCache('fullpagecache_treated_merged_css'));
        if (!$alreadyTreatedFiles || count($alreadyTreatedFiles) > $files) $alreadyTreatedFiles = array();
        $toTreatFiles = array_diff($files, $alreadyTreatedFiles);
        // If no css file to treat -> exit
        if (count($toTreatFiles) == 0) return;

        $host = Mage::getStoreConfig('fullpagecache/optimization_cdn/base');

        foreach ($toTreatFiles as $file) {
            $content = file_get_contents($file);

            $content = preg_replace_callback(
                '/' . $host . '/',
                create_function(
                    '$matches',
                    '$host = Mage::getStoreConfig(\'fullpagecache/optimization_cdn/base\');
                     $randomCdn = rand(1, Mage::getStoreConfig(\'fullpagecache/optimization_cdn/number\'));
                     $cdnHost= Mage::getStoreConfig(\'fullpagecache/optimization_cdn/cdn\');
                     return str_replace($host, str_replace(\'[X]\', $randomCdn, $cdnHost), $matches[0]);'
                ),
                $content
            );

            file_put_contents($file, $content);
        }

        Mage::app()->saveCache(serialize(array_merge($toTreatFiles, $alreadyTreatedFiles)), 'fullpagecache_treated_merged_css');
    }
}