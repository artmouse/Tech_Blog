<?php

/**
 * Post tab on product edit form
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Catalog_Product_Edit_Tab_Post extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Ultimate Module Creator
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('post_grid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        if ($this->getProduct()->getId()) {
            $this->setDefaultFilter(array('in_posts'=>1));
        }
    }

    /**
     * prepare the post collection
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Catalog_Product_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('tech_blog/post_collection');
        if ($this->getProduct()->getId()) {
            $constraint = 'related.product_id='.$this->getProduct()->getId();
        } else {
            $constraint = 'related.product_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('tech_blog/post_product')),
            'related.post_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Catalog_Product_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Catalog_Product_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_posts',
            array(
                'header_css_class'  => 'a-center',
                'type'  => 'checkbox',
                'name'  => 'in_posts',
                'values'=> $this->_getSelectedPosts(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header' => Mage::helper('tech_blog')->__('Title'),
                'align'  => 'left',
                'index'  => 'title',
                'renderer' => 'tech_blog/adminhtml_helper_column_renderer_relation',
                'params' => array(
                    'id' => 'getId'
                ),
                'base_link' => 'adminhtml/blog_post/edit',
            )
        );
        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('tech_blog')->__('Position'),
                'name'           => 'position',
                'width'          => 60,
                'type'           => 'number',
                'validate_class' => 'validate-number',
                'index'          => 'position',
                'editable'       => true,
            )
        );
        return parent::_prepareColumns();
    }

    /**
     * Retrieve selected posts
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    protected function _getSelectedPosts()
    {
        $posts = $this->getProductPosts();
        if (!is_array($posts)) {
            $posts = array_keys($this->getSelectedPosts());
        }
        return $posts;
    }

    /**
     * Retrieve selected posts
     *
     * @access protected
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts()
    {
        $posts = array();
        //used helper here in order not to override the product model
        $selected = Mage::helper('tech_blog/product')->getSelectedPosts(Mage::registry('current_product'));
        if (!is_array($selected)) {
            $selected = array();
        }
        foreach ($selected as $post) {
            $posts[$post->getId()] = array('position' => $post->getPosition());
        }
        return $posts;
    }

    /**
     * get row url
     *
     * @access public
     * @param Tech_Blog_Model_Post
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($item)
    {
        return '#';
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/postsGrid',
            array(
                'id'=>$this->getProduct()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Ultimate Module Creator
     */
    public function getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Tech_Blog_Block_Adminhtml_Catalog_Product_Edit_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() == 'in_posts') {
            $postIds = $this->_getSelectedPosts();
            if (empty($postIds)) {
                $postIds = 0;
            }
            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in'=>$postIds));
            } else {
                if ($postIds) {
                    $this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$postIds));
                }
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }
        return $this;
    }
}
