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
 * @filesource ./system/modules/languages/views/languages_grid.php
 */
?>

Ext.define('Toc.languages.LanguagesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'languages_id', 
        'code',
        'total_definitions',
        'module',
        'languages_name',
        'languages_flag'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'languages',
          action: 'list_languages'
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
    config.columns =[
      {header: '<?= lang('table_heading_languages'); ?>', dataIndex: 'languages_name', flex: 1},
      {header: '', width: 30, dataIndex: 'languages_flag'},
      {header: '<?= lang('table_heading_total_definitions'); ?>', width: 130, dataIndex: 'total_definitions'},
      {header: '<?= lang('table_heading_code'); ?>', width: 130, dataIndex: 'code'},
      {
        xtype: 'actioncolumn', 
        width: 120,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipEdit,
          iconCls: 'icon-action icon-edit-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', rec);
          },
          scope: this
        },
        {
          tooltip: '<?= lang('icon_export'); ?>',
          iconCls: 'icon-action icon-export-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('export', rec);
          },
          scope: this
        },
        {
          tooltip: TocLanguage.tipDelete,
          iconCls: 'icon-action icon-delete-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.onDelete(rec);
          },
          scope: this
        },
        {
          tooltip: '<?= lang('languages'); ?>',
          iconCls: 'icon-action icon-languages',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('translations', rec);
          },
          scope: this                
        }]
      }
    ];
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        iconCls: 'add',
        handler: function() {this.fireEvent('import');},
        scope: this
      },
      '-',
      {
        text: TocLanguage.btnUpload,
        iconCls: 'icon-upload',
        handler: function() {this.fireEvent('upload');},
        scope: this
      },
      '-', 
      {
        text: TocLanguage.btnDelete,
        iconCls: 'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      '-',
      { 
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
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
    
    this.addEvents({'import': true, 'edit': true, 'translations': true, 'export': true, 'upload': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var languagesId = record.get('languages_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'languages',
              action: 'delete_language',
              languages_id: languagesId,
              code: code
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
      keys.push(item.get('languages_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(Ext.JSON.encode(keys));
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              waitMsg: TocLanguage.formSubmitWaitMsg,
              url: Toc.CONF.CONN_URL,
              params: {
                module: 'languages',
                action: 'delete_languages',
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

/* End of file languages_grid.php */
/* Location: ./system/modules/languages/views/languages_grid.php */
