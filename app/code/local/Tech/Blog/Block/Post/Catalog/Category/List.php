<?php

/**
 * Post category list
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Post_Catalog_Category_List extends Mage_Core_Block_Template
{
    /**
     * get the list of products
     *
     * @access public
     * @return Mage_Catalog_Model_Resource_Category_Collection
     * @author Ultimate Module Creator
     */
    public function getCategoryCollection()
    {
        $collection = $this->getPost()->getSelectedCategoriesCollection();
        $collection->addAttributeToSelect('name');
        $collection->getSelect()->order('related.position');
        $collection->addAttributeToFilter('is_active', 1);
        return $collection;
    }

    /**
     * get current post
     *
     * @access public
     * @return Tech_Blog_Model_Post
     * @author Ultimate Module Creator
     */
    public function getPost()
    {
        return Mage::registry('current_post');
    }
}
