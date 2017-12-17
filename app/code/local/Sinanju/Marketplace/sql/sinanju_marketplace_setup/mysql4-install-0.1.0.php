<?php
/**
 * Mysql install
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
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS {$this->getTable('sinanju_marketplace_product')};
CREATE TABLE {$this->getTable('sinanju_marketplace_product')} (
    `entity_id` int(10) unsigned NOT NULL auto_increment,
    `product_id` int(10) unsigned NOT NULL default '0',
    `marketplace_code` varchar(255) default NULL,
    `min_qty_limit` int(10) unsigned NOT NULL default '0',
    `send_active` int(1) unsigned NOT NULL default '0',
    PRIMARY KEY  (`entity_id`),
    KEY `FK_marketplace_CODE` (`marketplace_code`),
    CONSTRAINT `FK_CATALOG_PRODUCT_ENTITY_ID` FOREIGN KEY (`product_id`) REFERENCES {$this->getTable('catalog_product_entity')} (`entity_id`) ON DELETE CASCADE ON UPDATE CASCADE
);"
);


$installer->endSetup();