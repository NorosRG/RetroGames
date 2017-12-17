<?php
/**
 * Block for Adminhtml Product Edit Marketplace tab
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

class Sinanju_Marketplace_Block_Adminhtml_Catalog_Product_Edit_Tab_Marketplace
    extends Mage_Adminhtml_Block_Widget
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * Construct method
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setTemplate('sinanju/marketplace/adminhtml/product/tab/marketplace.phtml');
    }

    /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Marketplace');
    }

    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Marketplace informations');
    }

    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Return all data product by marketplaces
     *
     * @return array
     */
    public function getAllMarketplaceInfo()
    {
        $vProductId = Mage::app()->getRequest()->getParam('id');
        $vMarketplaceCode = Mage::helper('sinanju_marketplace')->getMarketplaceCode();
        $vReturn = array();

        foreach ($vMarketplaceCode as $vCode) {
            $vMarketplaceProduct = Mage::getModel('sinanju_marketplace/product')->getCollection()
                ->addFieldToFilter('marketplace_code', $vCode)
                ->addFieldToFilter('product_id', $vProductId)
                ->getFirstItem();

            $vReturn[$vCode] = $vMarketplaceProduct;
        }

        return $vReturn;
    }
}