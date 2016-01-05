<?php

/**
 * Post admin block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_post';
        $this->_blockGroup         = 'tech_blog';
        parent::__construct();
        $this->_headerText         = Mage::helper('tech_blog')->__('Post');
        $this->_updateButton('add', 'label', Mage::helper('tech_blog')->__('Add Post'));

    }
}
