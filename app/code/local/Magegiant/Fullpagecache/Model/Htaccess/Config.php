<?php

class Magegiant_Fullpagecache_Model_Htaccess_Config
{
    const BEGIN_FULLPAGECACHE_DEFLATE = "## BEGIN_FULLPAGECACHE_DEFLATE";
    const END_FULLPAGECACHE_DEFLATE = "## END_FULLPAGECACHE_DEFLATE";

    const BEGIN_FULLPAGECACHE_EXPIRE = "## BEGIN_FULLPAGECACHE_EXPIRE";
    const END_FULLPAGECACHE_EXPIRE = "## END_FULLPAGECACHE_EXPIRE";

    const BEGIN_FULLPAGECACHE_ETAGS = "## BEGIN_FULLPAGECACHE_ETAGS";
    const END_FULLPAGECACHE_ETAGS = "## END_FULLPAGECACHE_ETAGS";

    public function activateHtaccessConfig($config, $tags = array())
    {
        if (!is_readable(Mage::getBaseDir() . DS . '.htaccess') || !is_writable(Mage::getBaseDir() . DS . '.htaccess')) {
            throw new Exception(Mage::helper('fullpagecache')->__('[wwwroot]/.htaccess file can\'t be read or writen'));
        }

        $htaccessContent = file_get_contents(Mage::getBaseDir() . DS . '.htaccess');

        if (preg_match('/' . $config . '/', $htaccessContent)) {
            return false;
        } else {
            if (!is_readable(Mage::getConfig()->getModuleDir('etc', 'Magegiant_Fullpagecache') . DS . 'htaccess.extraconfig')) {
                throw new Exception(Mage::helper('fullpagecache')->__('htaccess.extraconfig file in module Fullpagecache/etc can\'t be read'));
            }

            return file_put_contents(
                Mage::getBaseDir() . DS . '.htaccess',
                PHP_EOL . $this->extractActivateHtaccessConfig($config, file_get_contents(Mage::getConfig()->getModuleDir('etc', 'Magegiant_Fullpagecache') . DS . 'htaccess.extraconfig'), $tags) . PHP_EOL,
                FILE_APPEND | LOCK_EX
            );
        }
    }

    public function desactivateHtaccessConfig($config)
    {
        if (!is_readable(Mage::getBaseDir() . DS . '.htaccess') || !is_writable(Mage::getBaseDir() . DS . '.htaccess')) {
            throw new Exception(Mage::helper('fullpagecache')->__('[wwwroot]/.htaccess file can\'t be read or writen'));
        }

        $htaccessContent = file_get_contents(Mage::getBaseDir() . DS . '.htaccess');

        if (preg_match('/' . $config . '/', $htaccessContent)) {
            return file_put_contents(
                Mage::getBaseDir() . DS . '.htaccess',
                preg_replace('/' . $config . '(.|\n)*' . $this->getFinActivateConfig($config) . '(\r\n|\r|\n)*/', '', $htaccessContent),
                LOCK_EX
            );
        }
    }

    public function isActivatedHtaccessConfig($config)
    {
        if (!is_readable(Mage::getBaseDir() . DS . '.htaccess')) {
            return false;
        }

        return preg_match('/' . $config . '/', file_get_contents(Mage::getBaseDir() . DS . '.htaccess'));
    }

    private function getFinActivateConfig($config)
    {
        switch ($config) {
            case (self::BEGIN_FULLPAGECACHE_DEFLATE):
                return self::END_FULLPAGECACHE_DEFLATE;
            case (self::BEGIN_FULLPAGECACHE_EXPIRE):
                return self::END_FULLPAGECACHE_EXPIRE;
            case (self::BEGIN_FULLPAGECACHE_ETAGS):
                return self::END_FULLPAGECACHE_ETAGS;
            default:
                throw new Exception(Mage::helper('fullpagecache')->__('Invalid param config'));
        }
    }

    private function extractActivateHtaccessConfig($config, $htaccessExtraconfig, $tags = array())
    {
        $activatedHtaccessConfig = $config . Mage::helper('fullpagecache')->extractDelimitedContentFromString($config, $this->getFinActivateConfig($config), $htaccessExtraconfig) . $this->getFinActivateConfig($config);

        foreach ($tags as $tagKey => $value) {
            $activatedHtaccessConfig = str_replace('[' . $tagKey . ']', $value, $activatedHtaccessConfig);
        }

        return $activatedHtaccessConfig;
    }

    public function getTimeExpireConfigs()
    {
        if (!is_readable(Mage::getBaseDir() . DS . '.htaccess')) {
            return false;
        } else {
            $htaccessContent = file_get_contents(Mage::getBaseDir() . DS . '.htaccess');
            $helper = Mage::helper('fullpagecache');

            $timeExpireConfigs = new Varien_Data_Collection();
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_default', 'label' => $helper->__('Time expire default'), 'value' => $helper->extractDelimitedContentFromString('ExpiresDefault "access plus', '"', $htaccessContent, true))));
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_image', 'label' => $helper->__('Time expire image'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType image/jpg "access plus', '"', $htaccessContent, true))));
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_icon', 'label' => $helper->__('Time expire icon'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType image/vnd.microsoft.icon "access plus', '"', $htaccessContent, true))));
            //$timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_html', 'label' => $helper->__('Time expire html'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType text/html "access plus', '"', $htaccessContent, true))));
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_css', 'label' => $helper->__('Time expire css'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType text/css "access plus', '"', $htaccessContent, true))));
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_js', 'label' => $helper->__('Time expire js'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType application/javascript "access plus', '"', $htaccessContent, true))));
            $timeExpireConfigs->addItem(new Varien_Object(array('key' => 'time_expire_flash', 'label' => $helper->__('Time expire flash'), 'value' => $helper->extractDelimitedContentFromString('ExpiresByType application/x-shockwave-flash "access plus', '"', $htaccessContent, true))));

            return $timeExpireConfigs;
        }
    }
}