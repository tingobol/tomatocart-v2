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
 * @filesource modules/articles/views/articles_grid.php
 */
?>

Ext.define('Toc.articles.ArticlesGrid', {
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
        'articles_id', 
        'articles_status',
        'articles_order',
        'articles_name',
        'articles_categories_name'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'articles',
          action: 'list_articles'
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
      {header: '<?= lang('table_heading_articles'); ?>', dataIndex: 'articles_name', sortable: true, flex: 1},
      {header: '<?= lang('table_heading_articles_categories'); ?>', align: 'center', dataIndex: 'articles_categories_name'},
      {header: '<?= lang('table_heading_publish'); ?>', align: 'center', dataIndex: 'articles_status', renderer: statics.renderStatus},
      {header: '<?= lang('table_heading_sort_order'); ?>', align: 'center', dataIndex: 'articles_order', sortable: true},
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
    
    var dsCategories = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'articles',
          action: 'get_articles_categories',
          top: '1'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    config.cboCategories = Ext.create('Ext.form.ComboBox', {
      store: dsCategories,
      valueField: 'id', 
      displayField: 'text',
      emptyText: '<?= lang('top_articles_category'); ?>',
      triggerAction: 'all',
      listeners: {
        change: this.onSearch,
        scope: this
      }
    });
    
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
      config.cboCategories, 
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
  
  onSearch: function() {
    var categoriesId = this.cboCategories.getValue() || null;
    var filter = this.txtSearch.getValue() || null;
    var store = this.getStore();
    
    store.getProxy().extraParams['current_category_id'] = categoriesId;
    store.getProxy().extraParams['search'] = filter;
    store.load();
  },
  
  onDelete: function(record) {
    var articlesId = record.get('articles_id');
    
    Ext.MessageBox.confirm(
      TocLanguage.msgWarningTitle, 
      TocLanguage.msgDeleteConfirm, 
      function (btn) {
        if (btn == 'yes') {
          Ext.Ajax.request({
            waitMsg: TocLanguage.formSubmitWaitMsg,
            url: Toc.CONF.CONN_URL,
            params: {
              module: 'articles',
              action: 'delete_article',
              articles_id: articlesId
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
      keys.push(item.get('articles_id'));
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
                module: 'articles',
                action: 'delete_articles',
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
        var articlesId = this.getStore().getAt(index).get('articles_id');
        
        switch(action) {
          case 'status-off':
          case 'status-on':
            var flag = (action == 'status-on') ? 1 : 0;
            this.onAction(module, articlesId, flag, index);

            break;
        }
      }
    }
  },
  
  onAction: function(action, articlesId, flag, index) {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'articles',
        action: action,
        articles_id: articlesId,
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

/* End of file articles_grid.php */
/* Location: ./system/modules/articles/views/articles_grid.php */