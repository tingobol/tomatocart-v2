<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */
?>

Ext.define('Toc.faqs.FaqsGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus : function(status) {
      if(status == 1) {
        return '<img class="img-button" src="<?php echo icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_red_light.gif'); ?>" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="<?php echo icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?php echo icon_status_url('icon_status_red.gif'); ?>" />';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields: ['faqs_id', 'faqs_status', 'faqs_order', 'faqs_question'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : '<?php echo site_url('faqs/list_faqs'); ?>',
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
      { header: '<?php echo lang('table_heading_faq'); ?>', dataIndex: 'faqs_question', flex: 1, sortable: true},
      { header: '<?php echo lang('table_heading_publish'); ?>', dataIndex: 'faqs_status', renderer: statics.renderStatus, align: 'center'},
      { header: '<?php echo lang('table_heading_sort_order'); ?>', dataIndex: 'faqs_order', align: 'center', sortable: true},
      {
        xtype:'actioncolumn', 
        width: 60,
        header: '<?php echo lang("table_heading_action"); ?>',
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
    
    config.txtSearch = Ext.create('Ext.form.TextField', {
      width: 150
    });
    
    config.tbar = [
      {
        text: TocLanguage.btnAdd,
        iconCls:'add',
        handler: function() {this.fireEvent('create');},
        scope: this
      },
      '-', 
      {
        text: TocLanguage.btnDelete,
        iconCls:'remove',
        handler: this.onBatchDelete,
        scope: this
      },
      '-',
      { 
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }, 
      '->', 
      config.txtSearch,
      ' ',
      { 
        text: '',
        iconCls: 'search',
        handler: this.onSearch,
        scope: this
      }
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    this.addEvents({'create': true, 'notifysuccess': true, 'edit': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record){
    var faqsId = record.get('faqs_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm,
      function(btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            url: '<?php echo site_url('faqs/delete_faq'); ?>',
            params: {
              faqs_id: faqsId
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
  },
  
  onBatchDelete: function() {
    var selections = this.selModel.getSelection();
    
    keys = [];
    Ext.each(selections, function(item) {
      keys.push(item.get('faqs_id'));
    });
    
    if (keys.length > 0) {
      var batch = Ext.JSON.encode(keys);
      
      Ext.MessageBox.confirm(
        TocLanguage.msgWarningTitle, 
        TocLanguage.msgDeleteConfirm,
        function(btn) {
          if (btn == 'yes') {
            Ext.Ajax.request({
              url: '<?php echo site_url('faqs/delete_faqs'); ?>',
              params: {
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
        }, this);

    }else{
       Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onClick: function(view, record, item, index, e) {
    var action = false;
    var module = 'set_status';
    
    var btn = e.getTarget(".img-button");
    if (!Ext.isEmpty(btn)) {
      action = btn.className.replace(/img-button btn-/, '').trim();

      if (action != 'img-button') {
        var faqsId = this.getStore().getAt(index).get('faqs_id');
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            var flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, faqsId, flag, index);

            break;
        }
      }
    }
  },
  
  onAction: function(action, faqsId, flag, index) {
    Ext.Ajax.request({
      url: '<?php echo site_url('faqs'); ?>/' + action,
      params: {
        faqs_id: faqsId,
        flag: flag
      },
      callback: function(options, success, response) {
        var result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          var store = this.getStore();
          
          store.getAt(index).set('status', flag);
          
          this.fireEvent('notifysuccess', result.feedback);
          this.onRefresh();
        }else {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, result.feedback);
        }
      },
      scope: this
    });
  },
  
  onSearch: function() {
    var filter = this.txtSearch.getValue() || null;
    var store = this.getStore();
    
    store.getProxy().extraParams['search'] = filter;
    store.load();
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file faqs_grid.php */
/* Location: ./templates/base/web/views/faqs/faqs_grid.php */