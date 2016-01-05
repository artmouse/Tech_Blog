<?php

/**
 * Post admin edit tabs
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('post_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('tech_blog')->__('Post'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_post',
            array(
                'label'   => Mage::helper('tech_blog')->__('Post'),
                'title'   => Mage::helper('tech_blog')->__('Post'),
                'content' => $this->getLayout()->createBlock(
                    'tech_blog/adminhtml_post_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        $this->addTab(
            'form_meta_post',
            array(
                'label'   => Mage::helper('tech_blog')->__('Meta'),
                'title'   => Mage::helper('tech_blog')->__('Meta'),
                'content' => $this->getLayout()->createBlock(
                    'tech_blog/adminhtml_post_edit_tab_meta'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_post',
                array(
                    'label'   => Mage::helper('tech_blog')->__('Store views'),
                    'title'   => Mage::helper('tech_blog')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'tech_blog/adminhtml_post_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        $this->addTab(
            'products',
            array(
                'label' => Mage::helper('tech_blog')->__('Associated products'),
                'url'   => $this->getUrl('*/*/products', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        $this->addTab(
            'categories',
            array(
                'label' => Mage::helper('tech_blog')->__('Associated categories'),
                'url'   => $this->getUrl('*/*/categories', array('_current' => true)),
                'class' => 'ajax'
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve post entity
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
