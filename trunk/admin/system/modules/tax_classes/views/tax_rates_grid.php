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
 * @filesource ./system/modules/tax_classes/views/tax_rates_grid.php
 */
?>

Ext.define('Toc.tax_classes.TaxRatesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.title = '<?= lang('section_tax_rates'); ?>';
    config.region = 'east';
    config.split = true;
    config.minWidth = 280;
    config.maxWidth = 360;
    config.width = 300;
    config.border = false;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['tax_rates_id', 'tax_priority', 'tax_rate', 'geo_zone_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'tax_classes',
          action: 'list_tax_rates'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.selModel = Ext.create('Ext.selection.CheckboxModel');
    
    config.columns = [
      { header: '<?= lang('table_heading_tax_rate_zone'); ?>', dataIndex: 'geo_zone_name', flex: 1},
      { header: '<?= lang('table_heading_tax_rate_priority'); ?>', dataIndex: 'tax_priority', width: 70},
      { header: '<?= lang('table_heading_tax_rate'); ?>', dataIndex: 'tax_rate', width: 80, align: 'right'},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {'record': rec, 'taxClassId': this.taxClassId, 'taxClassTitle': this.taxClassTitle});
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
        iconCls: 'add',
        handler: function() {this.fireEvent('create', this.taxClassId);},
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
      { text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    this.taxClassId = null;
    this.taxClassTitle = null;
    
    this.addEvents({'create': true, 'notifysuccess': true, 'edit': true});
    
    this.callParent([config]);
  },
  
  iniGrid: function(record) {
    this.taxClassId = record.get('tax_class_id');
    this.taxClassTitle = record.get('tax_class_title');
    
    this.getStore().getProxy().extraParams['tax_class_id'] = this.taxClassId;
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var taxRatesId = record.get('tax_rates_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'tax_classes',
              action: 'delete_tax_rate',
              rateId: taxRatesId
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
      keys.push(item.get('tax_rates_id'));
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
                module: 'tax_classes',
                action: 'delete_tax_rates',
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

/* End of file tax_rates_grid.php */
/* Location: ./system/modules/tax_classes/tax_rates_grid.php */