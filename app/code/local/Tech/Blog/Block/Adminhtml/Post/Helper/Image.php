<?php

/**
 * Post image field renderer helper
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Adminhtml_Post_Helper_Image extends Varien_Data_Form_Element_Image
{
    /**
     * get the url of the image
     *
     * @access protected
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getUrl()
    {
        $url = false;
        if ($this->getValue()) {
            $url = Mage::helper('tech_blog/post_image')->getImageBaseUrl().
                $this->getValue();
        }
        return $url;
    }
}
