<?php

/**
 * Category helper
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Helper_Category extends Tech_Blog_Helper_Data
{

    /**
     * get the selected posts for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts(Mage_Catalog_Model_Category $category)
    {
        if (!$category->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection($category) as $post) {
                $posts[] = $post;
            }
            $category->setSelectedPosts($posts);
        }
        return $category->getData('selected_posts');
    }

    /**
     * get post collection for a category
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @return Tech_Blog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection(Mage_Catalog_Model_Category $category)
    {
        $collection = Mage::getResourceSingleton('tech_blog/post_collection')
            ->addCategoryFilter($category);
        return $collection;
    }
}
