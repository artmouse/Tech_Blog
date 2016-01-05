<?php

/**
 * Post admin controller
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Adminhtml_Blog_PostController extends Tech_Blog_Controller_Adminhtml_Blog
{
    /**
     * init the post
     *
     * @access protected
     * @return Tech_Blog_Model_Post
     */
    protected function _initPost()
    {
        $postId  = (int) $this->getRequest()->getParam('id');
        $post    = Mage::getModel('tech_blog/post');
        if ($postId) {
            $post->load($postId);
        }
        Mage::register('current_post', $post);
        return $post;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('tech_blog')->__('Blog'))
             ->_title(Mage::helper('tech_blog')->__('Posts'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit post - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $postId    = $this->getRequest()->getParam('id');
        $post      = $this->_initPost();
        if ($postId && !$post->getId()) {
            $this->_getSession()->addError(
                Mage::helper('tech_blog')->__('This post no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getPostData(true);
        if (!empty($data)) {
            $post->setData($data);
        }
        Mage::register('post_data', $post);
        $this->loadLayout();
        $this->_title(Mage::helper('tech_blog')->__('Blog'))
             ->_title(Mage::helper('tech_blog')->__('Posts'));
        if ($post->getId()) {
            $this->_title($post->getTitle());
        } else {
            $this->_title(Mage::helper('tech_blog')->__('Add post'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new post action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save post - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('post')) {
            try {
                $data = $this->_filterDates($data, array('event_at'));
                $post = $this->_initPost();
                $post->addData($data);
                $imageName = $this->_uploadAndGetName(
                    'image',
                    Mage::helper('tech_blog/post_image')->getImageBaseDir(),
                    $data
                );
                $post->setData('image', $imageName);
                $products = $this->getRequest()->getPost('products', -1);
                if ($products != -1) {
                    $post->setProductsData(Mage::helper('adminhtml/js')->decodeGridSerializedInput($products));
                }
                $categories = $this->getRequest()->getPost('category_ids', -1);
                if ($categories != -1) {
                    $categories = explode(',', $categories);
                    $categories = array_unique($categories);
                    $post->setCategoriesData($categories);
                }
                $post->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tech_blog')->__('Post was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $post->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setPostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                if (isset($data['image']['value'])) {
                    $data['image'] = $data['image']['value'];
                }
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tech_blog')->__('There was a problem saving the post.')
                );
                Mage::getSingleton('adminhtml/session')->setPostData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tech_blog')->__('Unable to find post to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete post - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $post = Mage::getModel('tech_blog/post');
                $post->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tech_blog')->__('Post was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tech_blog')->__('There was an error deleting post.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('tech_blog')->__('Could not find post to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete post - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tech_blog')->__('Please select posts to delete.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                    $post = Mage::getModel('tech_blog/post');
                    $post->setId($postId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('tech_blog')->__('Total of %d posts were successfully deleted.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tech_blog')->__('There was an error deleting posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tech_blog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('tech_blog/post')->load($postId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tech_blog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Display In Homesite change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massInCmsAction()
    {
        $postIds = $this->getRequest()->getParam('post');
        if (!is_array($postIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('tech_blog')->__('Please select posts.')
            );
        } else {
            try {
                foreach ($postIds as $postId) {
                $post = Mage::getSingleton('tech_blog/post')->load($postId)
                    ->setInCms($this->getRequest()->getParam('flag_in_cms'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d posts were successfully updated.', count($postIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('tech_blog')->__('There was an error updating posts.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
            ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    /**
     * get grid of products action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function productsgridAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->getLayout()->getBlock('post.edit.tab.product')
            ->setPostProducts($this->getRequest()->getPost('post_products', null));
        $this->renderLayout();
    }

    /**
     * get categories action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesAction()
    {
        $this->_initPost();
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * get child categories action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function categoriesJsonAction()
    {
        $this->_initPost();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('tech_blog/adminhtml_post_edit_tab_categories')
                ->getCategoryChildrenJson($this->getRequest()->getParam('category'))
        );
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'post.csv';
        $content    = $this->getLayout()->createBlock('tech_blog/adminhtml_post_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'post.xls';
        $content    = $this->getLayout()->createBlock('tech_blog/adminhtml_post_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'post.xml';
        $content    = $this->getLayout()->createBlock('tech_blog/adminhtml_post_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('tech_blog/post');
    }
}
