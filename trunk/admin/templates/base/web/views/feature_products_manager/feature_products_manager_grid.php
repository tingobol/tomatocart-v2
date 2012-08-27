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
 * @filesource ./system/modules/feature_products_manager/views/feature_products_manager_grid.php
 */
?>

Ext.define('Toc.feature_products_manager.ProductsManagerGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'products_id', 
        'products_name',
        'sort_order'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'feature_products_manager',
          action: 'list_products'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    config.selType = 'cellmodel';
    config.columns =[
      {header: '<?= lang('table_heading_products'); ?>', dataIndex: 'products_name', flex: 1},
      {
        header: '<?= lang('table_heading_sort_order'); ?>', 
        align: 'right', 
        dataIndex: 'sort_order',
        editor: {
          xtype:'textfield',
          allowBlank:false
        }
      },
      {
        xtype: 'actioncolumn', 
        width: 40,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-delete-record',
          tooltip: TocLanguage.tipDelete,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.onDelete(rec);
          },
          scope: this                
        }]
      }
    ];
    
    config.plugins = [
      Ext.create('Ext.grid.plugin.CellEditing', {
        clicksToEdit: 1,
        listeners: {
          'edit': this.onGrdAfterEdit,
          scope: this
        }
      })
    ];
    
    config.dsCategories = Ext.create('Ext.data.Store', {
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
          module: 'feature_products_manager',
          action: 'get_categories'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.cboCategories = Ext.create('Ext.form.ComboBox', {
      listConfig: {
        getInnerTpl: function() {
          return '<div style="margin-left: {margin}px">{text}</div>';
        }
      },
      store: config.dsCategories,
      queryMode: 'remote',
      emptyText: '<?= lang("top_category"); ?>',
      valueField: 'id',
      displayField: 'text',
      triggerAction: 'all',
      listeners: {
        select: this.onSearch,
        scope: this
      }
    });
    
    config.tbar = [
      {
        text: TocLanguage.btnDelete,
        iconCls: 'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      {
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      config.cboCategories
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.callParent([config]);
  },
  
  onSearch: function(){
    var categoriesId = this.cboCategories.getValue() || null;
    var store = this.getStore();
          
    store.getProxy().extraParams['categories_id'] = categoriesId;
    store.load();
  },
  
  onDelete: function (record) {
    var productsId = record.get('products_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'feature_products_manager',
              action: 'delete_product',
              products_id: productsId
            },
            callback: function (options, success, response) {
              var result = Ext.decode(response.responseText);
              
              if (result.success == true) {
                this.fireEvent('notifysuccess', result.feedback);
                this.onRefresh();
              } else {
                Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
              }
            },
            scope: this
          });
        }
      }, 
      this
    );
  },
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('products_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              waitMsg: TocLanguage.formSubmitWaitMsg,
              url: Toc.CONF.CONN_URL,
              params: {
                module: 'feature_products_manager',
                action: 'delete_products',
                batch: batch
              },
              callback: function(options, success, response) {
                var result = Ext.decode(response.responseText);
                
                if (result.success == true) {
                  this.fireEvent('notifysuccess', result.feedback);
                  
                  this.onRefresh();
                } else {
                  Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
                }
              }, 
              scope: this
            });
          }
        }, 
        this
      );
    } else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onGrdAfterEdit: function(editor, e, options) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'feature_products_manager',
        action: 'update_sort_order',
        products_id: e.record.get("products_id"),
        sort_value: e.value
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
               
        if (result.success == true) {
          this.fireEvent('notifysuccess', result.feedback);
          this.onRefresh();
        } else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    });
  },
  
  onRefresh: function() {
    this.getStore().load();
  } 
});



/* End of file main.php */
/* Location: ./system/modules/feature_products_manager/views/feature_products_manager_grid.php */