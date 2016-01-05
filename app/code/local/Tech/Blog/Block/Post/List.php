<?php

/**
 * Post list block
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author Ultimate Module Creator
 */
class Tech_Blog_Block_Post_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $posts = Mage::getResourceModel('tech_blog/post_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $posts->setOrder('title', 'asc');
        $this->setPosts($posts);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Tech_Blog_Block_Post_List
     * @author Ultimate Module Creator
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'tech_blog.post.html.pager'
        )
        ->setCollection($this->getPosts());
        $this->setChild('pager', $pager);
        $this->getPosts()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
