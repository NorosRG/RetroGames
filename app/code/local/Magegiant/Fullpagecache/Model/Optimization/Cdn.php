<?php

class Magegiant_Fullpagecache_Model_Optimization_Cdn
{
    /**
     * Cdn HTML
     *
     * @param   string $html (normal)
     * @return   string $html (cdn)
     */
    public function cdnHtml($html)
    {
        if (Mage::app()->getRequest()->isSecure()) {
            return $html;
        }

        Mage::register('number', 1);

        $baseUrl = Mage::getStoreConfig('fullpagecache/optimization_cdn/base');

        $patternSearchImg = "/(<img[\d\D]+?src\=(\'|\")http\:\/\/)($baseUrl\/)([\d\D]+?)((\'|\")([\d\D]+?)\/>)/i";
        // 1.2.12 => 1.2.13
        $patternSearchScript = "/(<script type=\"text\/javascript\" src\=(\'|\")http\:\/\/)($baseUrl\/)([\d\D]+?)((\.js)([\d\D]+?)<\/script>)/i";
        $patternSearchLink = "/(<link[\d\D]+?stylesheet[\d\D]+?href\=(\'|\")http\:\/\/)($baseUrl\/)([\d\D]+?)((\.css)([\d\D]+?)\/>)/i";

        $html = preg_replace_callback($patternSearchImg, "Magegiant_Fullpagecache_Model_Optimization_Cdn::reTravaille", $html);
        $html = preg_replace_callback($patternSearchScript, "Magegiant_Fullpagecache_Model_Optimization_Cdn::reTravaille", $html);
        $html = preg_replace_callback($patternSearchLink, "Magegiant_Fullpagecache_Model_Optimization_Cdn::reTravaille", $html);

        return $html;
    }

    //function qui genere le pattern de remplacement 
    private static function reTravaille($matches)
    {
        $nombreCdn = Mage::getStoreConfig('fullpagecache/optimization_cdn/number'); //BO
        $futureBaseUrl = Mage::getStoreConfig('fullpagecache/optimization_cdn/cdn'); //BO
        //var_dump($matches);
        //init separation
        $newUrl = explode('[X]', $futureBaseUrl);
        $newUrlArray = array();

        $number = Mage::registry('number');
        if ($number > $nombreCdn) {
            $number = 1;
        }

        if (count($newUrl) > 1) {
            $newRandomUrl = $newUrl[0] . $number . $newUrl[1] . '/';
        } else {
            $newRandomUrl = $newUrl[0] . '/';
        }
        $number++;
        Mage::unregister('number');
        Mage::register('number', $number);

        return $matches[1] . $newRandomUrl . $matches[4] . $matches[5];
    }
}