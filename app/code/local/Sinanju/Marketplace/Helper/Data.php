<?php
/**
 * Helper Data
 *
 * PHP VERSION 7
 *
 * @category    Sinanju
 * @package     Sinanju_Marketplace
 * @author      Ronan <ronan.girault@sinanju.fr>
 * @copyright   Copyright (c) 2017 Sinanju
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @contributor Ronan <ronan.girault@sinanju.fr>,
 */ 
class Sinanju_Marketplace_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * All code of Marketplace integrate on module
     *
     * @var array
     */
    private $_fMarketplaceCode = array(
      'amazon',
    );

    /**
     * Get private variable
     *
     * @return array
     */
    public function getMarketplaceCode()
    {
        return $this->_fMarketplaceCode;
    }
}