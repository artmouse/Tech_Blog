<?php

/**
 * Post admin edit form
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        parent::__construct();
        $this->_blockGroup = 'tech_blog';
        $this->_controller = 'adminhtml_post';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('tech_blog')->__('Save Post')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('tech_blog')->__('Delete Post')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('tech_blog')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_post') && Mage::registry('current_post')->getId()) {
            return Mage::helper('tech_blog')->__(
                "Edit Post '%s'",
                $this->escapeHtml(Mage::registry('current_post')->getTitle())
            );
        } else {
            return Mage::helper('tech_blog')->__('Add Post');
        }
    }
}
