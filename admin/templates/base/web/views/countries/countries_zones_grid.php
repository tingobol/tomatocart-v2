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

Ext.define('Toc.countries.ZonesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.title = '<?= lang('section_zones'); ?>';
    config.border = false;
    config.region = 'east';
    config.split = true;
    config.minWidth = 240;
    config.maxWidth = 320;
    config.width = 280;
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['zone_id', 'zone_country_id', 'zone_code', 'zone_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'countries',
          action: 'list_zones'
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
      { header: '<?= lang('table_heading_zones'); ?>', dataIndex: 'zone_name', sortable: true, flex: 1},
      { header: '<?= lang('table_heading_zone_code'); ?>', dataIndex: 'zone_code', width: 60, sortable: true},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-edit-record',
          tooltip: TocLanguage.tipEdit,
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('edit', {'record': rec, 'countriesId': this.countriesId, 'countriesName': this.countriesName});
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
        handler: function() {this.fireEvent('create', this.countriesId);},
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
        handler:this.onRefresh,
        scope:this
      }
    ];
    
    this.addEvents({'notifysuccess': true, 'create': true, 'edit': true});
    
    this.countriesId = null;
    this.countriesName = null;
    
    
    this.callParent([config]);
  },
  
  iniGrid: function(record) {
    this.countriesId = record.get('countries_id');
    this.countriesName = record.get('countries_name');
    
    this.getStore().getProxy().extraParams['countries_id'] = this.countriesId;
    this.getStore().load();
  },
  
  onDelete: function(record) {
    var zoneId = record.get('zone_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'countries',
              action: 'delete_zone',
              zone_id: zoneId
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
      keys.push(item.get('zone_id'));
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
                module: 'countries',
                action: 'delete_zones',
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

/* End of file countries_zones_grid.php */
/* Location: ./system/modules/countries/views/countries_zones_grid.php */