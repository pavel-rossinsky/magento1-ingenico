<?php
/**
 * Setup script
 *
 * @category  Payment
 * @package   Netresearch_OPS
 * @author    André Herrn <andre.herrn@netresearch.de>
 * @author    Thomas Birke <thomas.birke@netresearch.de>
 * @copyright 2012 Netresearch App Factory AG (http://www.netresearch.de)
 * @license   OSL 3.0 (http://opensource.org/licenses/osl-3.0.php)
 * @link      http://www.netresearch.de/magento/
 */

/* @var $this Mage_Sales_Model_Mysql4_Setup */
$this->startSetup();
// Add column to grid table
$this->getConnection()->addColumn(
    $this->getTable('sales/order_grid'),
    'quote_id',
    "int(10) unsigned NOT NULL default '0' comment 'id of related quote'"
);
// Add key to table for this field,
// it will improve the speed of searching & sorting by the field
$this->getConnection()->addKey(
    $this->getTable('sales/order_grid'),
    'quote_id',
    'quote_id'
);

// Now you need to fullfill existing rows with data from address table
$select = $this->getConnection()->select();
$select->join(
    array('flat_order'=>$this->getTable('sales/order')),
    $this->getConnection()->quoteInto(
        'flat_order.entity_id = order_grid.entity_id',
        'quote_id'
    ),
    array('quote_id' => 'quote_id')
);
$this->getConnection()->query(
    $select->crossUpdateFromSelect(
        array('order_grid' => $this->getTable('sales/order_grid'))
    )
);

$this->endSetup();