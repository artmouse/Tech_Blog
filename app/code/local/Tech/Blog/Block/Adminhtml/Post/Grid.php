<?php

/**
 * Post admin grid block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('postGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('tech_blog/post')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('tech_blog')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('tech_blog')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('tech_blog')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('tech_blog')->__('Enabled'),
                    '0' => Mage::helper('tech_blog')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'short_desc',
            array(
                'header' => Mage::helper('tech_blog')->__('Short Description'),
                'index'  => 'short_desc',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'event_at',
            array(
                'header' => Mage::helper('tech_blog')->__('Event Date'),
                'index'  => 'event_at',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'in_cms',
            array(
                'header' => Mage::helper('tech_blog')->__('Display In Homesite'),
                'index'  => 'in_cms',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('tech_blog')->__('Yes'),
                    '0' => Mage::helper('tech_blog')->__('No'),
                )

            )
        );
        $this->addColumn(
            'url_key',
            array(
                'header' => Mage::helper('tech_blog')->__('URL key'),
                'index'  => 'url_key',
            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('tech_blog')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('tech_blog')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('tech_blog')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('tech_blog')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('tech_blog')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('tech_blog')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('tech_blog')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('tech_blog')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('post');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('tech_blog')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('tech_blog')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('tech_blog')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tech_blog')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('tech_blog')->__('Enabled'),
                            '0' => Mage::helper('tech_blog')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'in_cms',
            array(
                'label'      => Mage::helper('tech_blog')->__('Change Display In Homesite'),
                'url'        => $this->getUrl('*/*/massInCms', array('_current'=>true)),
                'additional' => array(
                    'flag_in_cms' => array(
                        'name'   => 'flag_in_cms',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('tech_blog')->__('Display In Homesite'),
                        'values' => array(
                                '1' => Mage::helper('tech_blog')->__('Yes'),
                                '0' => Mage::helper('tech_blog')->__('No'),
                            )

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Tech_Blog_Model_Post
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Tech_Blog_Model_Resource_Post_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Tech_Blog_Block_Adminhtml_Post_Grid
     * @author Ultimate Module Creator
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
