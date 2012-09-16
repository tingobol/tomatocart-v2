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
 * @filesource system/modules/languages/views/translations_add_dialog.php
 */
?>

Ext.define('Toc.languages.TranslationAddDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'translation-add-dialog-win';
    config.title = '<?php echo lang('action_heading_add_definition'); ?>';
    config.width = 500;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope: this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() { 
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  show: function (languagesId, group) {
    this.languagesId = languagesId || null;

    if (this.languagesId > 0) {
      this.frmLanguage.baseParams['languages_id'] = this.languagesId;
    
      this.dsGroups.getProxy().extraParams['languages_id'] = this.languagesId;
      this.dsGroups.on('load', function(){
        this.cboGroups.setValue(group);
      }, this);
      
      this.dsGroups.load();
    }
    
    this.callParent();
  },
  
  buildForm: function() {
    this.dsGroups = Ext.create('Ext.data.Store', {
      fields:[
        'id', 
        'text'
      ],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'languages',
          action: 'get_groups'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboGroups = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_group_selection'); ?>',
      name: 'definition_group',
      store: this.dsGroups,
      queryMode: 'local',
      displayField: 'text',
      valueField: 'id',
      triggerAction: 'all'
    });
    
    this.frmLanguage = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'languages',
        action: 'add_translation'
      },
      border: false,
      bodyPadding: 8,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 120,
        anchor: '97%',
        allowBlank: false
      },
      items: [
        this.cboGroups,
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_definition_key'); ?>', 
          name: 'definition_key'
        },
        {
          xtype: 'textarea',
          fieldLabel: '<?php echo lang("field_definition_value"); ?>',
          name: 'definition_value'
        }
      ]
    });
    
    return this.frmLanguage;
  },
  
  submitForm : function() {
    this.frmLanguage.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.fireEvent('savesuccess', action.result.feedback);
         this.close();  
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },  
      scope: this
    });   
  }
});

/* End of file translations_add_dialog.php */
/* Location: system/modules/languages/views/translations_add_dialog.php */
