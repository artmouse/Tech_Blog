<?php

/**
 * Post view block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Block_Post_View extends Mage_Core_Block_Template
{
    /**
     * get the current post
     *
     * @access public
     * @return mixed (Tech_Blog_Model_Post|null)
     * @author Ultimate Module Creator
     */
    public function getCurrentPost()
    {
        return Mage::registry('current_post');
    }
}
