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
 * @filesource ./system/modules/image_groups/views/image_groups_grid.php
 */
?>

Ext.define('Toc.image_groups.ImageGroupsGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['id', 'title'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'image_groups',
          action: 'list_image_groups'
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
    
    config.columns = [
      { header: '<?= lang('table_heading_image_groups'); ?>', dataIndex: 'title', flex: 1},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },{
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
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        iconCls:'add',
        handler: function() {this.fireEvent('create');},
        scope: this
      },
      ' ', 
      {
        text: TocLanguage.btnDelete,
        iconCls:'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      ' ',
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'create': true, 'notifysuccess': true, 'edit': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var imageGroupsId = record.get('id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'image_groups',
              action: 'delete_image_group',
              image_groups_id: imageGroupsId
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
      keys.push(item.get('id'));
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
                module: 'image_groups',
                action: 'delete_image_groups',
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
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file image_groups_grid.php */
/* Location: ./system/modules/image_groups/views/image_groups_grid.php */