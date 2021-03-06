<?php

/**
 * Post edit form
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare form
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Edit_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(
            array(
                'id'         => 'edit_form',
                'action'     => $this->getUrl(
                    '*/*/save',
                    array(
                        'id' => $this->getRequest()->getParam('id')
                    )
                ),
                'method'     => 'post',
                'enctype'    => 'multipart/form-data'
            )
        );
        $form->setUseContainer(true);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
