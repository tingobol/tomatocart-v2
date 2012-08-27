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
 * @filesource modules/categories/views/categories_general_panel.php
 */
?>

Ext.define('Toc.categories.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_general'); ?>';
    config.border = false;
    config.bodyPadding = 8;
    config.layout = 'anchor';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    this.dsParentCategories = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text',
        'margin'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'categories',
          action: 'list_parent_category'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboParentCategories = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{text}</div>';
        }
      },
      fieldLabel: '<?= lang("field_parent_category"); ?>',
      store: this.dsParentCategories,
      queryMode: 'local',
      valueField: 'id',
      displayField: 'text',
      name: 'parent_category_id',
      triggerAction: 'all'
    });
    
    items.push(this.cboParentCategories);
    
    <?php
      $i = 1; 
    
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'categories_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name'); ?>',
          labelStyle: '<?= worldflag_url($l['country_iso']); ?>',
          allowBlank: false
        });
        
        items.push(lang<?php echo $l['id'] ?>);
        
    <?php
        $i++;
      }
    ?>
    
    items.push({xtype: 'filefield', fieldLabel: '<?= lang("field_image"); ?>', name: 'image'});
    items.push({xtype: 'panel', name: 'categories_image', id: 'categories_image_panel', border: false});
    
    items.push({
      layout: 'column',
      border: false,
      items:[{
        id: 'status',
        border: false,
        style: "padding-right: 5px;",
        items:[{fieldLabel: '<?= lang('field_status'); ?>', xtype:'radio', id: 'statusEnable', name: 'categories_status', boxLabel: '<?= lang('status_enabled'); ?>', inputValue: '1', checked: true}]
      },{
        border: false,
        items: [{fieldLabel: '<?= lang('status_disabled'); ?>', boxLabel: '<?= lang('status_disabled'); ?>', xtype:'radio', name: 'categories_status', hideLabel: true, inputValue: '0'}]
      }]
    });
    
    items.push({xtype: 'numberfield', fieldLabel: '<?= lang("field_sort_order"); ?>', name: 'sort_order'});
    
    return items;
  }
});

/* End of file categories_general_panel.php */
/* Location: ./system/modules/categories/views/categories_general_panel.php */

