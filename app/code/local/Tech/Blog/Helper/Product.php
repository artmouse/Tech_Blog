<?php

/**
 * Product helper
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Helper_Product extends Tech_Blog_Helper_Data
{

    /**
     * get the selected posts for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return array()
     * @author Ultimate Module Creator
     */
    public function getSelectedPosts(Mage_Catalog_Model_Product $product)
    {
        if (!$product->hasSelectedPosts()) {
            $posts = array();
            foreach ($this->getSelectedPostsCollection($product) as $post) {
                $posts[] = $post;
            }
            $product->setSelectedPosts($posts);
        }
        return $product->getData('selected_posts');
    }

    /**
     * get post collection for a product
     *
     * @access public
     * @param Mage_Catalog_Model_Product $product
     * @return Tech_Blog_Model_Resource_Post_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedPostsCollection(Mage_Catalog_Model_Product $product)
    {
        $collection = Mage::getResourceSingleton('tech_blog/post_collection')
            ->addProductFilter($product);
        return $collection;
    }
}
