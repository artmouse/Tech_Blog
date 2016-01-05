<?php

/**
 * Post list on category page block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Catalog_Category_List_Post extends Mage_Core_Block_Template
{
    /**
     * get the list of posts
     *
     * @access protected
     * @return Tech_Blog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getPostCollection()
    {
        if (!$this->hasData('post_collection')) {
            $category = Mage::registry('current_category');
            $collection = Mage::getResourceSingleton('tech_blog/post_collection')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1)
                ->addCategoryFilter($category);
            $collection->getSelect()->order('related_category.position', 'ASC');
            $this->setData('post_collection', $collection);
        }
        return $this->getData('post_collection');
    }
}
