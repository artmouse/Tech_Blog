<?php

/**
 * Post - Category relation resource model collection
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Resource_Post_Category_Collection extends Mage_Catalog_Model_Resource_Category_Collection
{
    /**
     * remember if fields have been joined
     *
     * @var bool
     */
    protected $_joinedFields = false;

    /**
     * join the link table
     *
     * @access public
     * @return Tech_Blog_Model_Resource_Post_Category_Collection
     * @author Ultimate Module Creator
     */
    public function joinFields()
    {
        if (!$this->_joinedFields) {
            $this->getSelect()->join(
                array('related' => $this->getTable('tech_blog/post_category')),
                'related.category_id = e.entity_id',
                array('position')
            );
            $this->_joinedFields = true;
        }
        return $this;
    }

    /**
     * add post filter
     *
     * @access public
     * @param Tech_Blog_Model_Post | int $post
     * @return Tech_Blog_Model_Resource_Post_Category_Collection
     * @author Ultimate Module Creator
     */
    public function addPostFilter($post)
    {
        if ($post instanceof Tech_Blog_Model_Post) {
            $post = $post->getId();
        }
        if (!$this->_joinedFields) {
            $this->joinFields();
        }
        $this->getSelect()->where('related.post_id = ?', $post);
        return $this;
    }
}
