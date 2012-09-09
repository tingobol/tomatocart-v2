<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource ./system/modules/product_variants/views/product_variants_main_panel.php
 */
?>

Ext.define('Toc.product_variants.MainPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.layout = 'border';
    config.border = false;
    
    config.grdVariantsEntries = Ext.create('Toc.product_variants.ProductVariantsEntriesGrid');
    config.grdVariantsGroups = Ext.create('Toc.product_variants.ProductVariantsGroupsGrid');
    
    config.grdVariantsGroups.on('selectchange', this.onGrdVariantsGroupsSelectChange, this);
    config.grdVariantsGroups.getStore().on('load', this.onGrdVariantsGroupsLoad, this);
    config.grdVariantsEntries.getStore().on('load', this.onGrdVariantsEntriesLoad, this);
    
    config.items = [config.grdVariantsGroups, config.grdVariantsEntries];
    
    this.callParent([config]);    
  },
  
  onGrdVariantsGroupsLoad: function() {
    if (this.grdVariantsGroups.getStore().getCount() > 0) {
      this.grdVariantsGroups.getSelectionModel().select(0);
      var record = this.grdVariantsGroups.getStore().getAt(0);
      
      this.onGrdVariantsGroupsSelectChange(record);
    }
  },
  
  onGrdVariantsGroupsSelectChange: function(record) {
    this.grdVariantsEntries.setTitle('<?php echo lang("heading_product_variants_title");?>:  '+ record.get('products_variants_groups_name'));
    this.grdVariantsEntries.iniGrid(record);
  },
  
  onGrdVariantsEntriesLoad: function() {
    var record = this.grdVariantsGroups.getSelectionModel().getLastSelected() || null;
    if (record) {
      record.set('total_entries', this.grdVariantsEntries.getStore().getCount());
    }
  } 
});

/* End of file product_variants_main_panel.php */
/* Location: ./system/modules/product_variants/product_variants_main_panel.php */
