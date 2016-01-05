<?php

/**
 * Post - product controller
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/ProductController.php");
class Tech_Blog_Adminhtml_Blog_Post_Catalog_ProductController extends Mage_Adminhtml_Catalog_ProductController
{
    /**
     * construct
     *
     * @access protected
     * @return void
     * @author Ultimate Module Creator
     */
    protected function _construct()
    {
        // Define module dependent translate
        $this->setUsedModuleName('Tech_Blog');
    }

    /**
     * posts in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function postsAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
            ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }

    /**
     * posts grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function postsGridAction()
    {
        $this->_initProduct();
        $this->loadLayout();
        $this->getLayout()->getBlock('product.edit.tab.post')
            ->setProductPosts($this->getRequest()->getPost('product_posts', null));
        $this->renderLayout();
    }
}
