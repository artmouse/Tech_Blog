<?php

/**
 * Post - category controller
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
require_once ("Mage/Adminhtml/controllers/Catalog/CategoryController.php");
class Tech_Blog_Adminhtml_Blog_Post_Catalog_CategoryController extends Mage_Adminhtml_Catalog_CategoryController
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
     * posts grid in the catalog page
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function postsgridAction()
    {
        $this->_initCategory();
        $this->loadLayout();
        $this->getLayout()->getBlock('category.edit.tab.post')
            ->setCategoryPosts($this->getRequest()->getPost('category_posts', null));
        $this->renderLayout();
    }
}
