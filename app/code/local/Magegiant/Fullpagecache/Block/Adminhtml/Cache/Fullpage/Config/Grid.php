<?php

class Magegiant_Fullpagecache_Block_Adminhtml_Cache_Fullpage_Config_Grid extends Magegiant_Fullpagecache_Block_Adminhtml_Cache_Common_Grid
{
    public function __construct()
    {
        parent::__construct();
        // This is the primary key of the database
        $this->setDefaultSort('config_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('fullpagecache/cache_fullpage_config')->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('friendly_entry', array(
            'header' => Mage::helper('fullpagecache')->__('Friendly Entry'),
            'align' => 'left',
            'index' => 'friendly_entry',
        ));

        $this->addColumn('full_action_name', array(
            'header' => Mage::helper('fullpagecache')->__('Full Action Name'),
            'align' => 'left',
            'index' => 'full_action_name',
        ));

        $this->addColumn('cache_lifetime', array(
            'header' => Mage::helper('fullpagecache')->__('Cache Lifetime'),
            'align' => 'left',
            'index' => 'cache_lifetime',
            'getter' => 'getFormattedCacheLifetime'
        ));

        $this->addColumn('store_id', array(
            'header' => Mage::helper('cms')->__('Store View'),
            'index' => 'store_id',
            'type' => 'store',
            'store_all' => true,
            'store_view' => true,
            'sortable' => false
        ));

        $this->addColumn('status', array(
            'header' => Mage::helper('fullpagecache')->__('Actived'),
            'width' => '120',
            'align' => 'left',
            'index' => 'activated',
            'type' => 'options',
            'options' => array(0 => Mage::helper('fullpagecache')->__('Disabled'), 1 => Mage::helper('fullpagecache')->__('Enabled')),
            'frame_callback' => array($this, 'decorateStatus')
        ));

        return parent::_prepareColumns();
    }

    /**
     * Add mass-actions to grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('config_id');
        $this->getMassactionBlock()->setFormFieldName('config_ids');

        $this->getMassactionBlock()->addItem('enable', array(
            'label' => Mage::helper('fullpagecache')->__('Enable'),
            'url' => $this->getUrl('*/*/massEnable'),
        ));

        $this->getMassactionBlock()->addItem('disable', array(
            'label' => Mage::helper('fullpagecache')->__('Disable'),
            'url' => $this->getUrl('*/*/massDisable'),
        ));

        $this->getMassactionBlock()->addItem('refresh', array(
            'label' => Mage::helper('fullpagecache')->__('Refresh'),
            'url' => $this->getUrl('*/*/massRefresh'),
        ));

        $this->getMassactionBlock()->addItem('delete', array(
            'label' => Mage::helper('fullpagecache')->__('Delete'),
            'url' => $this->getUrl('*/*/massDelete'),
        ));

        return $this;
    }
}