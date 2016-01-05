<?php 

/**
 * Post helper
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Helper_Post extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the posts list page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPostsUrl()
    {
        if ($listKey = Mage::getStoreConfig('tech_blog/post/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('tech_blog/post/index');
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('tech_blog/post/breadcrumbs');
    }
}
