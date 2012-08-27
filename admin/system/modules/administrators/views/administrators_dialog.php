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
 * @filesource ./system/modules/administrators/views/administrators_dialog.php
 */
?>

Ext.define('Toc.administrators.AdministratorsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    this.treeLoading = config.treeLoading;
    this.aID = config.aID;
    
    config.id = 'administrators_dialog-win';
    config.title = '<?= lang('action_heading_new_administrator'); ?>';
    config.width = 400;
    config.height = 480;
    config.modal = true;
    config.iconCls = 'icon-administrators-win';
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function (administratorsId) {
    var administratorsId = administratorsId || null;
    
    if (administratorsId > 0) {
      this.frmAdministrator.form.baseParams['aID'] = administratorsId;
      
      this.frmAdministrator.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'administrators',
          action: 'load_administrator'
        },
        success: function(form, action) {
          Toc.administrators.AdministratorsDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmAdministrator = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'administrators',
        action: 'save_administrator'
      },
      border: false,
      layout: 'border',
      fieldDefaults: {
        anchor: '98%',
        labelSeparator: ''
      },
      items: [
        this.getAccessPanel(), 
        this.getAdminPanel()
      ]
    });
    
    return this.frmAdministrator;
  },
  
  getAdminPanel: function() {
    
    this.pnlAdmin = Ext.create('Ext.Panel', {
      region: 'north',
      border: false,
      bodyPadding: 10,
      layout: 'anchor',
      items: [
        {
          xtype: 'textfield', 
          fieldLabel: '<?= lang('field_username'); ?>', 
          name: 'user_name', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?= lang('field_password'); ?>', 
          name: 'user_password', 
          allowBlank: this.aID > 0 ? true : false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?= lang('field_email'); ?>', 
          name: 'email_address', 
          allowBlank: false
        }
      ]
    });
    
    return this.pnlAdmin;
  },
  
  getAccessPanel: function() {
    this.chkGlobal = Ext.create('Ext.form.Checkbox', {
      name: 'access_globaladmin', 
      boxLabel: '<?= lang('global_access'); ?>',
      listeners: {
        change: function(chk, checked) {
          if(checked)
            this.refreshTree('on');
          else
            this.refreshTree('off');
        },
        scope: this
      }
    });
    
    var extraParams = {
      module: 'administrators',
      action: 'get_accesses'
    };
    
    if (this.aID > 0)
    {
      extraParams.aID = this.aID;
    }    
   
    var dsAccessTree = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: extraParams
      },
      root: {
        id: '0',
        text: '<?= lang('top_category'); ?>',
        leaf: false,
        expandable: true,  
        expanded: true  
      }
    });
    
    this.pnlAccessTree = Ext.create('Ext.tree.TreePanel', {
      name: 'access_modules',
      id: 'access_modules',
      region: 'center',
      store: dsAccessTree,
      bodyPadding: 10,
      rootVisible: false,
      border: false,
      autoScroller: true,
      dockedItems: [{
        xtype: 'toolbar',
        items: [
          this.chkGlobal        
        ]
      }]
    });
    
    return this.pnlAccessTree;
  },
  
  refreshTree: function(param) {
    var proxy = this.pnlAccessTree.getStore().getProxy();
    
    proxy.extraParams['global'] = param;
    
    this.pnlAccessTree.getStore().on('load', function() {
      if (proxy.extraParams['global'] == param)
      {
        this.pnlAccessTree.expandAll();
      }
    }, this);
    
    this.pnlAccessTree.getStore().load();
  },
  
  loadAccessTree: function(administratorsId) {
    this.pnlAccessTree.getStore().on('beforeload', function() {
      var proxy = this.pnlAccessTree.getStore().getProxy();
    
      proxy.extraParams['aID'] = administratorsId;
    }, this);
  },
  
  submitForm : function() {
    var modules = [];
    var checkedRecords = this.pnlAccessTree.getChecked();
    
    if (!Ext.isEmpty(checkedRecords)) {
      Ext.each(checkedRecords, function(record) {
        modules.push(record.get('text'));
      });
    }
    
    this.frmAdministrator.form.submit({
      params: {
        modules: Ext.JSON.encode(modules)
      },
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file administrators_dialog.php */
/* Location: ./system/modules/administrators/views/administrators_dialog.php */
