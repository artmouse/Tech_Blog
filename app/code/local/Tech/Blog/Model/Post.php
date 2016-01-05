<?php

/**
 * Post model
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
class Tech_Blog_Model_Post extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'tech_blog_post';
    const CACHE_TAG = 'tech_blog_post';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'tech_blog_post';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'post';
    protected $_productInstance = null;
    protected $_categoryInstance = null;

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('tech_blog/post');
    }

    /**
     * before save post
     *
     * @access protected
     * @return Tech_Blog_Model_Post
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the post details page
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getPostUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('tech_blog/post/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('tech_blog/post/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('tech_blog/post/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Ultimate Module Creator
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * get the post Description
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getDesc()
    {
        $desc = $this->getData('desc');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($desc);
        return $html;
    }

    /**
     * get the post More
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getMore()
    {
        $more = $this->getData('more');
        $helper = Mage::helper('cms');
        $processor = $helper->getBlockTemplateProcessor();
        $html = $processor->filter($more);
        return $html;
    }

    /**
     * save post relation
     *
     * @access public
     * @return Tech_Blog_Model_Post
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->savePostRelation($this);
        $this->getCategoryInstance()->savePostRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return Tech_Blog_Model_Post_Product
     * @author Ultimate Module Creator
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('tech_blog/post_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return Tech_Blog_Resource_Post_Product_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }

    /**
     * get category relation model
     *
     * @access public
     * @return Tech_Blog_Model_Post_Category
     * @author Ultimate Module Creator
     */
    public function getCategoryInstance()
    {
        if (!$this->_categoryInstance) {
            $this->_categoryInstance = Mage::getSingleton('tech_blog/post_category');
        }
        return $this->_categoryInstance;
    }

    /**
     * get selected categories array
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getSelectedCategories()
    {
        if (!$this->hasSelectedCategories()) {
            $categories = array();
            foreach ($this->getSelectedCategoriesCollection() as $category) {
                $categories[] = $category;
            }
            $this->setSelectedCategories($categories);
        }
        return $this->getData('selected_categories');
    }

    /**
     * Retrieve collection selected categories
     *
     * @access public
     * @return Tech_Blog_Resource_Post_Category_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedCategoriesCollection()
    {
        $collection = $this->getCategoryInstance()->getCategoryCollection($this);
        return $collection;
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        $values['in_cms'] = 'No';

        return $values;
    }
    
}
