<?php

/**
 * Admin search model
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Adminhtml_Search_Post extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Tech_Blog_Model_Adminhtml_Search_Post
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('tech_blog/post_collection')
            ->addFieldToFilter('title', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $post) {
            $arr[] = array(
                'id'          => 'post/1/'.$post->getId(),
                'type'        => Mage::helper('tech_blog')->__('Post'),
                'name'        => $post->getTitle(),
                'description' => $post->getTitle(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/blog_post/edit',
                    array('id'=>$post->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
