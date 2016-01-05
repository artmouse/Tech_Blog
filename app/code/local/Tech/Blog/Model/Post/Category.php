<?php

/**
 * Post category model
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Post_Category extends Mage_Core_Model_Abstract
{
    /**
     * Initialize resource
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        $this->_init('tech_blog/post_category');
    }

    /**
     * Save data for post-category relation
     *
     * @access public
     * @param  Tech_Blog_Model_Post $post
     * @return Tech_Blog_Model_Post_Category
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post)
    {
        $data = $post->getCategoriesData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    /**
     * get categories for post
     *
     * @access public
     * @param Tech_Blog_Model_Post $post
     * @return Tech_Blog_Model_Resource_Post_Category_Collection
     * @author Ultimate Module Creator
     */
    public function getCategoryCollection($post)
    {
        $collection = Mage::getResourceModel('tech_blog/post_category_collection')
            ->addPostFilter($post);
        return $collection;
    }
}
