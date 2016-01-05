<?php

/**
 * Post edit form tab
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Tech_Blog_Block_Adminhtml_Post_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('post_');
        $form->setFieldNameSuffix('post');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'post_form',
            array('legend' => Mage::helper('tech_blog')->__('Post'))
        );
        $fieldset->addType(
            'image',
            Mage::getConfig()->getBlockClassName('tech_blog/adminhtml_post_helper_image')
        );
        $wysiwygConfig = Mage::getSingleton('cms/wysiwyg_config')->getConfig();

        $fieldset->addField(
            'title',
            'text',
            array(
                'label' => Mage::helper('tech_blog')->__('Title'),
                'name'  => 'title',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('tech_blog')->__('Image'),
                'name'  => 'image',

           )
        );

        $fieldset->addField(
            'short_desc',
            'text',
            array(
                'label' => Mage::helper('tech_blog')->__('Short Description'),
                'name'  => 'short_desc',
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'desc',
            'editor',
            array(
                'label' => Mage::helper('tech_blog')->__('Description'),
                'name'  => 'desc',
            'config' => $wysiwygConfig,
                'required'  => true,
                'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'more',
            'editor',
            array(
                'label' => Mage::helper('tech_blog')->__('More'),
                'name'  => 'more',
            'config' => $wysiwygConfig,

           )
        );

        $fieldset->addField(
            'event_at',
            'date',
            array(
                'label' => Mage::helper('tech_blog')->__('Event Date'),
                'name'  => 'event_at',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'in_cms',
            'select',
            array(
                'label' => Mage::helper('tech_blog')->__('Display In Homesite'),
                'name'  => 'in_cms',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('tech_blog')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('tech_blog')->__('No'),
                ),
            ),
           )
        );
        $fieldset->addField(
            'url_key',
            'text',
            array(
                'label' => Mage::helper('tech_blog')->__('Url key'),
                'name'  => 'url_key',
                'note'  => Mage::helper('tech_blog')->__('Relative to Website Base URL')
            )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('tech_blog')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('tech_blog')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('tech_blog')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_post')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_post')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getPostData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getPostData());
            Mage::getSingleton('adminhtml/session')->setPostData(null);
        } elseif (Mage::registry('current_post')) {
            $formValues = array_merge($formValues, Mage::registry('current_post')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
