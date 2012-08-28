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
 * @filesource
 */
?>

Ext.define('Toc.manufacturers.MetaInfoPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_meta'); ?>';
    config.activeTab = 0;
    config.deferredRender = false;
    config.border = false;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    
    <?php
      foreach(lang_get_all() as $l) {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso'] ?>-win',
          layout: 'anchor',
          border: false,
          bodyPadding: 8,
          items: [
            {xtype: 'textfield',fieldLabel: '<?= lang('field_page_title'); ?>' , name: 'page_title[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?= lang('field_meta_keywords') ?>', name: 'meta_keywords[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?= lang('field_meta_description') ?>', name: 'meta_description[<?php echo $l['id']; ?>]'},
            {
              xtype: 'textfield',
              fieldLabel: '<?= lang('field_manufacturer_url'); ?>',
              labelStyle: '<?= worldflag_url($l['country_iso']); ?>',
              name: 'manufacturers_friendly_url[<?php echo $l['id']; ?>]'
            }
          ]
        });
        
        panels.push(lang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return panels;
  }
});