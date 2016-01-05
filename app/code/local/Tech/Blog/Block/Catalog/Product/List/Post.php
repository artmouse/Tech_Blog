<?php

/**
 * Post list on product page block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Catalog_Product_List_Post extends Mage_Catalog_Block_Product_Abstract
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
            $product = Mage::registry('product');
            $collection = Mage::getResourceSingleton('tech_blog/post_collection')
                ->addStoreFilter(Mage::app()->getStore())
                ->addFieldToFilter('status', 1)
                ->addProductFilter($product);
            $collection->getSelect()->order('related_product.position', 'ASC');
            $this->setData('post_collection', $collection);
        }
        return $this->getData('post_collection');
    }
}
