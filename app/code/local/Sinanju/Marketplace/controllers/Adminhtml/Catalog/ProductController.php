<?php
/**
 * Product Controller
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

require_once(Mage::getModuleDir('controllers','Mage_Adminhtml').DS.'Catalog'.DS.'ProductController.php');

class Sinanju_Marketplace_Adminhtml_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * Get custom products grid and serializer block
     */
    public function marketplaceAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('catalog.product.edit.tab.marketplace');
        $this->renderLayout();
    }


}