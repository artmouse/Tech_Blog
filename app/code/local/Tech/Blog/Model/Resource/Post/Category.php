<?php

/**
 * Post - Categories relation model
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Resource_Post_Category extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * initialize resource model
     *
     * @access protected
     * @return void
     * @see Mage_Core_Model_Resource_Abstract::_construct()
     * @author Ultimate Module Creator
     */
    protected function  _construct()
    {
        $this->_init('tech_blog/post_category', 'rel_id');
    }

    /**
     * Save post - category relations
     *
     * @access public
     * @param Tech_Blog_Model_Post $post
     * @param array $data
     * @return Tech_Blog_Model_Resource_Post_Category
     * @author Ultimate Module Creator
     */
    public function savePostRelation($post, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('post_id=?', $post->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $categoryId) {
            if (!empty($categoryId)) {
                $insert = array(
                    'post_id' => $post->getId(),
                    'category_id'   => $categoryId,
                    'position'      => 1
                );
                $this->_getWriteAdapter()->insertOnDuplicate($this->getMainTable(), $insert, array_keys($insert));
            }
        }
        return $this;
    }

    /**
     * Save  category - post relations
     *
     * @access public
     * @param Mage_Catalog_Model_Category $category
     * @param array $data
     * @return Tech_Blog_Model_Resource_Post_Category
     * @author Ultimate Module Creator
     */
    public function saveCategoryRelation($category, $data)
    {
        if (!is_array($data)) {
            $data = array();
        }
        $deleteCondition = $this->_getWriteAdapter()->quoteInto('category_id=?', $category->getId());
        $this->_getWriteAdapter()->delete($this->getMainTable(), $deleteCondition);

        foreach ($data as $postId => $info) {
            $this->_getWriteAdapter()->insert(
                $this->getMainTable(),
                array(
                    'post_id' => $postId,
                    'category_id'   => $category->getId(),
                    'position'      => @$info['position']
                )
            );
        }
        return $this;
    }
}
