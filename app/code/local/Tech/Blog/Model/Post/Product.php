<?php

/**
 * Post product model
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Post_Product extends Mage_Core_Model_Abstract
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
        $this->_init('tech_blog/post_product');
    }

    /**
     * Save data for post-product relation
     * @access public
     * @param  Tech_Blog_Model_Post $post
     * @return Tech_Blog_Model_Post_Product
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post)
    {
        $data = $post->getProductsData();
        if (!is_null($data)) {
            $this->_getResource()->savePostRelation($post, $data);
        }
        return $this;
    }

    /**
     * get products for post
     *
     * @access public
     * @param Tech_Blog_Model_Post $post
     * @return Tech_Blog_Model_Resource_Post_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection($post)
    {
        $collection = Mage::getResourceModel('tech_blog/post_product_collection')
            ->addPostFilter($post);
        return $collection;
    }
}
