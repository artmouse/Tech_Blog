<?php

/**
 * Post product list
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Post_Catalog_Product_List extends Mage_Core_Block_Template
{
    /**
     * get the list of products
     *
     * @access public
     * @return Mage_Catalog_Model_Resource_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getProductCollection()
    {
        $collection = $this->getPost()->getSelectedProductsCollection();
        $collection->addAttributeToSelect('name');
        $collection->addUrlRewrite();
        $collection->getSelect()->order('related.position');
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
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
