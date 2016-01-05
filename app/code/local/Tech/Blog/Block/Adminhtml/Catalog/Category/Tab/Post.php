<?php

/**
 * Post tab on category edit form
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Catalog_Category_Tab_Post extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('catalog_category_post');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->getCategory()->getId()) {
            $this->setDefaultFilter(array('in_posts'=>1));
        }
    }

    /**
     * get current category
     *
     * @access public
     * @return Mage_Catalog_Model_Category|null
     * @author Ultimate Module Creator
     */
    public function getCategory()
    {
        return Mage::registry('current_category');
    }

    /**
     * prepare the collection
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Catalog_Category_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('tech_blog/post_collection');
        if ($this->getCategory()->getId()) {
            $constraint = 'related.category_id='.$this->getCategory()->getId();
        } else {
            $constraint = 'related.category_id=0';
        }
        $collection->getSelect()->joinLeft(
            array('related' => $collection->getTable('tech_blog/post_category')),
            'related.post_id=main_table.entity_id AND '.$constraint,
            array('position')
        );
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * Prepare the columns
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Catalog_Category_Tab_Post
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'in_posts',
            array(
                'header_css_class'  => 'a-center',
                'type'   => 'checkbox',
                'name'   => 'in_posts',
                'values' => $this->_getSelectedPosts(),
                'align'  => 'center',
                'index'  => 'entity_id'
            )
        );
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('tech_blog')->__('Id'),
                'type'   => 'number',
                'align'  => 'left',
                'index'  => 'entity_id',
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
        $posts = $this->getCategoryPosts();
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
        //used helper here in order not to override the category model
        $selected = Mage::helper('tech_blog/category')->getSelectedPosts(Mage::registry('current_category'));
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
            'adminhtml/blog_post_catalog_category/postsgrid',
            array(
                'id'=>$this->getCategory()->getId()
            )
        );
    }

    /**
     * Add filter
     *
     * @access protected
     * @param object $column
     * @return Tech_Blog_Block_Adminhtml_Catalog_Category_Tab_Post
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
