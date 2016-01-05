<?php

/**
 * meta information tab
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Edit_Tab_Meta extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Edit_Tab_Meta
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setFieldNameSuffix('post');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'post_meta_form',
            array('legend' => Mage::helper('tech_blog')->__('Meta information'))
        );
        $fieldset->addField(
            'meta_title',
            'text',
            array(
                'label' => Mage::helper('tech_blog')->__('Meta-title'),
                'name'  => 'meta_title',
            )
        );
        $fieldset->addField(
            'meta_description',
            'textarea',
            array(
                'name'      => 'meta_description',
                'label'     => Mage::helper('tech_blog')->__('Meta-description'),
              )
        );
        $fieldset->addField(
            'meta_keywords',
            'textarea',
            array(
                'name'      => 'meta_keywords',
                'label'     => Mage::helper('tech_blog')->__('Meta-keywords'),
            )
        );
        $form->addValues(Mage::registry('current_post')->getData());
        return parent::_prepareForm();
    }
}
