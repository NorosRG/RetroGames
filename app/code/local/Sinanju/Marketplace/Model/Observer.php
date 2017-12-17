<?php
/**
 * Observer
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

class Sinanju_Marketplace_Model_Observer
{
    /**
     * Save data for product related to marketplace
     *
     * @param Varien_Event_Observer $observer the observer
     *
     * @return void
     */
    public function saveMarketplaceProduct(Varien_Event_Observer $observer)
    {
        try {
            $vMarketplaceData = Mage::app()->getRequest()->getPost('marketplace');
            $vProductId = $observer->getEvent()->getProduct()->getId();

            foreach ($vMarketplaceData as $vCode => $vData) {
                if ($vData['send'] == 'on') {
                    $vData['send'] = 1;
                } else {
                    $vData['send'] = 0;
                }

                $vMarketplaceProduct = Mage::getModel('sinanju_marketplace/product')->getCollection()
                    ->addFieldToFilter('marketplace_code', $vCode)
                    ->addFieldToFilter('product_id', $vProductId)
                    ->getFirstItem();

                $vMarketplaceProduct->setMarketplaceCode($vCode);
                $vMarketplaceProduct->setProductId($vProductId);
                $vMarketplaceProduct->setMinQtyLimit($vData['min_qty']);
                $vMarketplaceProduct->setSendActive($vData['send']);

                $vMarketplaceProduct->save();
            }

        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }

    }
}
