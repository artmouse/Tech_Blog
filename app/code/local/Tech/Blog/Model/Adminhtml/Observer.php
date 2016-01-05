<?php

/**
 * Adminhtml observer
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Adminhtml_Observer
{
    /**
     * check if tab can be added
     *
     * @access protected
     * @param Mage_Catalog_Model_Product $product
     * @return bool
     * @author Ultimate Module Creator
     */
    protected function _canAddTab($product)
    {
        if ($product->getId()) {
            return true;
        }
        if (!$product->getAttributeSetId()) {
            return false;
        }
        $request = Mage::app()->getRequest();
        if ($request->getParam('type') == 'configurable') {
            if ($request->getParam('attributes')) {
                return true;
            }
        }
        return false;
    }

    /**
     * add the post tab to products
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tech_Blog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addProductPostBlock($observer)
    {
        $block = $observer->getEvent()->getBlock();
        $product = Mage::registry('product');
        if ($block instanceof Mage_Adminhtml_Block_Catalog_Product_Edit_Tabs && $this->_canAddTab($product)) {
            $block->addTab(
                'posts',
                array(
                    'label' => Mage::helper('tech_blog')->__('Posts'),
                    'url'   => Mage::helper('adminhtml')->getUrl(
                        'adminhtml/blog_post_catalog_product/posts',
                        array('_current' => true)
                    ),
                    'class' => 'ajax',
                )
            );
        }
        return $this;
    }

    /**
     * save post - product relation
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tech_Blog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveProductPostData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('posts', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $product = Mage::registry('product');
            $postProduct = Mage::getResourceSingleton('tech_blog/post_product')
                ->saveProductRelation($product, $post);
        }
        return $this;
    }
    /**
     * add the post tab to categories
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tech_Blog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function addCategoryPostBlock($observer)
    {
        $tabs = $observer->getEvent()->getTabs();
        $content = $tabs->getLayout()->createBlock(
            'tech_blog/adminhtml_catalog_category_tab_post',
            'category.post.grid'
        )->toHtml();
        $serializer = $tabs->getLayout()->createBlock(
            'adminhtml/widget_grid_serializer',
            'category.post.grid.serializer'
        );
        $serializer->initSerializerBlock(
            'category.post.grid',
            'getSelectedPosts',
            'posts',
            'category_posts'
        );
        $serializer->addColumnInputName('position');
        $content .= $serializer->toHtml();
        $tabs->addTab(
            'post',
            array(
                'label'   => Mage::helper('tech_blog')->__('Posts'),
                'content' => $content,
            )
        );
        return $this;
    }

    /**
     * save post - category relation
     *
     * @access public
     * @param Varien_Event_Observer $observer
     * @return Tech_Blog_Model_Adminhtml_Observer
     * @author Ultimate Module Creator
     */
    public function saveCategoryPostData($observer)
    {
        $post = Mage::app()->getRequest()->getPost('posts', -1);
        if ($post != '-1') {
            $post = Mage::helper('adminhtml/js')->decodeGridSerializedInput($post);
            $category = Mage::registry('category');
            $postCategory = Mage::getResourceSingleton('tech_blog/post_category')
                ->saveCategoryRelation($category, $post);
        }
        return $this;
    }
}
