<?php

/**
 * Blog module install script
 *
 * @category    Tech
 * @package     Tech_Blog
 * @author      Ultimate Module Creator
 */
$this->startSetup();
$table = $this->getConnection()
    ->newTable($this->getTable('tech_blog/post'))
    ->addColumn(
        'entity_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Post ID'
    )
    ->addColumn(
        'title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Title'
    )
    ->addColumn(
        'image',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Image'
    )
    ->addColumn(
        'short_desc',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(
            'nullable'  => false,
        ),
        'Short Description'
    )
    ->addColumn(
        'desc',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(
            'nullable'  => false,
        ),
        'Description'
    )
    ->addColumn(
        'more',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'More'
    )
    ->addColumn(
        'event_at',
        Varien_Db_Ddl_Table::TYPE_DATETIME, 255,
        array(),
        'Event Date'
    )
    ->addColumn(
        'in_cms',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Display In Homesite'
    )
    ->addColumn(
        'status',
        Varien_Db_Ddl_Table::TYPE_SMALLINT, null,
        array(),
        'Enabled'
    )
    ->addColumn(
        'url_key',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'URL key'
    )
    ->addColumn(
        'meta_title',
        Varien_Db_Ddl_Table::TYPE_TEXT, 255,
        array(),
        'Meta title'
    )
    ->addColumn(
        'meta_keywords',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta keywords'
    )
    ->addColumn(
        'meta_description',
        Varien_Db_Ddl_Table::TYPE_TEXT, '64k',
        array(),
        'Meta description'
    )
    ->addColumn(
        'updated_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Modification Time'
    )
    ->addColumn(
        'created_at',
        Varien_Db_Ddl_Table::TYPE_TIMESTAMP,
        null,
        array(),
        'Post Creation Time'
    ) 
    ->setComment('Post Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('tech_blog/post_store'))
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'nullable'  => false,
            'primary'   => true,
        ),
        'Post ID'
    )
    ->addColumn(
        'store_id',
        Varien_Db_Ddl_Table::TYPE_SMALLINT,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Store ID'
    )
    ->addIndex(
        $this->getIdxName(
            'tech_blog/post_store',
            array('store_id')
        ),
        array('store_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_store',
            'post_id',
            'tech_blog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('tech_blog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_store',
            'store_id',
            'core/store',
            'store_id'
        ),
        'store_id',
        $this->getTable('core/store'),
        'store_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->setComment('Posts To Store Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('tech_blog/post_product'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'product_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Product ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'tech_blog/post_product',
            array('product_id')
        ),
        array('product_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_product',
            'post_id',
            'tech_blog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('tech_blog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_product',
            'product_id',
            'catalog/product',
            'entity_id'
        ),
        'product_id',
        $this->getTable('catalog/product'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'tech_blog/post_product',
            array('post_id', 'product_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('post_id', 'product_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Post to Product Linkage Table');
$this->getConnection()->createTable($table);
$table = $this->getConnection()
    ->newTable($this->getTable('tech_blog/post_category'))
    ->addColumn(
        'rel_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'identity'  => true,
            'nullable'  => false,
            'primary'   => true,
        ),
        'Relation ID'
    )
    ->addColumn(
        'post_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Post ID'
    )
    ->addColumn(
        'category_id',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'unsigned'  => true,
            'nullable'  => false,
            'default'   => '0',
        ),
        'Category ID'
    )
    ->addColumn(
        'position',
        Varien_Db_Ddl_Table::TYPE_INTEGER,
        null,
        array(
            'nullable'  => false,
            'default'   => '0',
        ),
        'Position'
    )
    ->addIndex(
        $this->getIdxName(
            'tech_blog/post_category',
            array('category_id')
        ),
        array('category_id')
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_category',
            'post_id',
            'tech_blog/post',
            'entity_id'
        ),
        'post_id',
        $this->getTable('tech_blog/post'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addForeignKey(
        $this->getFkName(
            'tech_blog/post_category',
            'category_id',
            'catalog/category',
            'entity_id'
        ),
        'category_id',
        $this->getTable('catalog/category'),
        'entity_id',
        Varien_Db_Ddl_Table::ACTION_CASCADE,
        Varien_Db_Ddl_Table::ACTION_CASCADE
    )
    ->addIndex(
        $this->getIdxName(
            'tech_blog/post_category',
            array('post_id', 'category_id'),
            Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
        ),
        array('post_id', 'category_id'),
        array('type' => Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE)
    )
    ->setComment('Post to Category Linkage Table');
$this->getConnection()->createTable($table);
$this->addAttribute(
    'catalog_product',
    'post',
    array(
        'group'             => 'General',
        'backend'           => '',
        'frontend'          => '',
        'class'             => '',
        'default'           => '',
        'label'             => 'Post',
        'input'             => 'select',
        'type'              => 'int',
        'source'            => 'tech_blog/post_source',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'is_visible'        => 1,
        'required'          => 0,
        'searchable'        => 0,
        'filterable'        => 0,
        'unique'            => 0,
        'comparable'        => 0,
        'visible_on_front'  => 0,
        'user_defined'      => 1,
    )
);
$this->addAttribute(
    'catalog_category',
    'post',
    array(
        'group'             => 'General Information',
        'backend'           => '',
        'frontend'          => '',
        'class'             => '',
        'default'           => '',
        'label'             => 'Post',
        'input'             => 'select',
        'type'              => 'int',
        'source'            => 'tech_blog/post_source',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required'          => 0,
        'unique'            => 0,
        'user_defined'      => 1,
    )
);
$this->endSetup();
