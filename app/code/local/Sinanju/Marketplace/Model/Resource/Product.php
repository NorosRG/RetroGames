<?php
 /** 
 * Resource Marketplace Product
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
class Sinanju_Marketplace_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{

    protected function _construct()
    {
        $this->_init('sinanju_marketplace/sinanju_marketplace_product', 'entity_id');
    }

}