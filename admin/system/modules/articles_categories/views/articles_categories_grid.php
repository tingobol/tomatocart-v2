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
 * @filesource modules/articles_categories/views/articles_categories_grid.php
 */
?>

Ext.define('Toc.articles_categories.ArticlesCategoriesGrid', {
  extend: 'Ext.grid.Panel',
  
  statics: {
    renderStatus : function(status) {
      if(status == 1) {
        return '<img class="img-button" src="<?= icon_status_url('icon_status_green.gif'); ?>" />&nbsp;<img class="img-button btn-status-off" style="cursor: pointer" src="<?= icon_status_url('icon_status_red_light.gif'); ?>" />';
      }else {
        return '<img class="img-button btn-status-on" style="cursor: pointer" src="<?= icon_status_url('icon_status_green_light.gif'); ?>" />&nbsp;<img class="img-button" src= "<?= icon_status_url('icon_status_red.gif'); ?>" />';
      }
    }
  },
  
  constructor: function(config) {
    var statics = this.statics();
    
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'articles_categories_id', 
        'articles_categories_status',
        'articles_categories_name',
        'articles_categories_order'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'articles_categories',
          action: 'list_articles_categories'
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
      {header: '<?= lang('table_heading_categories'); ?>', dataIndex: 'articles_categories_name', sortable: true, flex: 1},
      {header: '<?= lang('table_heading_publish'); ?>', align: 'center', renderer: statics.renderStatus, dataIndex: 'articles_categories_status'},
      {header: '<?= lang('table_heading_categories_order'); ?>', align: 'center', sortable: true, dataIndex: 'articles_categories_order'},
      {
        xtype: 'actioncolumn', 
        width: 80,
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
    
    config.listeners = {
      itemclick: this.onClick
    };
    
    this.addEvents({'create': true, 'edit': true, 'notifysuccess': true});
    
    this.callParent([config]);
  },
  
  onDelete: function(record) {
    var categoriesId = record.get('articles_categories_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'articles_categories',
              action: 'delete_article_category',
              articles_categories_id: categoriesId
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
      keys.push(item.get('articles_categories_id'));
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
                module: 'articles_categories',
                action: 'delete_articles_categories',
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
  
  onClick: function(view, record, item, index, e) {
    var action = false;
    var module = 'set_status';
    
    var btn = e.getTarget(".img-button");
    if (!Ext.isEmpty(btn)) {
      action = btn.className.replace(/img-button btn-/, '').trim();

      if (action != 'img-button') {
        var categoriesId = this.getStore().getAt(index).get('articles_categories_id');
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            var flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, categoriesId, flag, index);

            break;
        }
      }
    }
  },
  
  onAction: function(action, categoriesId, flag, index) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'articles_categories',
        action: action,
        articles_categories_id: categoriesId,
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
  
  onRefresh: function() {
    this.getStore().load();
  }  
});

/* End of file articles_categories_grid.php */
/* Location: ./system/modules/articles_categories/views/articles_categories_grid.php */