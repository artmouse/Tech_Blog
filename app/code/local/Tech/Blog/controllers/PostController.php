<?php

/**
 * Post front contrller
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_PostController extends Mage_Core_Controller_Front_Action
{

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
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('tech_blog/post')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('tech_blog')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'posts',
                    array(
                        'label' => Mage::helper('tech_blog')->__('Posts'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('tech_blog/post')->getPostsUrl());
        }
        if ($headBlock) {
            $headBlock->setTitle(Mage::getStoreConfig('tech_blog/post/meta_title'));
            $headBlock->setKeywords(Mage::getStoreConfig('tech_blog/post/meta_keywords'));
            $headBlock->setDescription(Mage::getStoreConfig('tech_blog/post/meta_description'));
        }
        $this->renderLayout();
    }

    /**
     * init Post
     *
     * @access protected
     * @return Tech_Blog_Model_Post
     * @author Ultimate Module Creator
     */
    protected function _initPost()
    {
        $postId   = $this->getRequest()->getParam('id', 0);
        $post     = Mage::getModel('tech_blog/post')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($postId);
        if (!$post->getId()) {
            return false;
        } elseif (!$post->getStatus()) {
            return false;
        }
        return $post;
    }

    /**
     * view post action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function viewAction()
    {
        $post = $this->_initPost();
        if (!$post) {
            $this->_forward('no-route');
            return;
        }
        Mage::register('current_post', $post);
        $this->loadLayout();
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if ($root = $this->getLayout()->getBlock('root')) {
            $root->addBodyClass('blog-post blog-post' . $post->getId());
        }
        if (Mage::helper('tech_blog/post')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label'    => Mage::helper('tech_blog')->__('Home'),
                        'link'     => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'posts',
                    array(
                        'label' => Mage::helper('tech_blog')->__('Posts'),
                        'link'  => Mage::helper('tech_blog/post')->getPostsUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'post',
                    array(
                        'label' => $post->getTitle(),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', $post->getPostUrl());
        }
        if ($headBlock) {
            if ($post->getMetaTitle()) {
                $headBlock->setTitle($post->getMetaTitle());
            } else {
                $headBlock->setTitle($post->getTitle());
            }
            $headBlock->setKeywords($post->getMetaKeywords());
            $headBlock->setDescription($post->getMetaDescription());
        }
        $this->renderLayout();
    }
}
