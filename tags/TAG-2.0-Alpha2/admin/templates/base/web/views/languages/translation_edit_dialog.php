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
 * @filesource system/modules/languages/views/translation_edit_dialog.php
 */
?>

Ext.define('Toc.languages.TranslationEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'translation-edit-win';
    config.title = config.definitionKey;
    config.layout = 'fit';
    config.width = 400;
    config.height = 240;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm(config);
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
        },
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  buildForm: function(config) {
    this.txtTranslation = Ext.create('Ext.form.TextArea', {
      region: 'center',
      emptyText: TocLanguage.gridNoRecords,
      name: 'definition_value',
      allowBlank: false,
      value: config.definitionValue
    });
    
    this.frmTranslationEdit = Ext.create('Ext.form.Panel', {
      baseParams: {
        module: 'languages',
        languages_id: config.languagesId,
        group: config.group,
        definition_key: config.definitionKey
      },
      layout: 'border',
      border: false,
      bodyPadding: 8,
      items: [
        this.txtTranslation
      ]
    });
    
    return this.frmTranslationEdit;
  },
  
  submitForm : function() {
    this.frmTranslationEdit.form.submit({
      url: Toc.CONF.CONN_URL,
      params: { 
        module: 'languages',
        action: 'update_translation'
      },
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();   
      },    
      failure: function(form, action) {
        if(action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },   
      scope: this
    });    
  }
});

/* End of file translations_dialog.php */
/* Location: system/modules/languages/views/translations_dialog.php */
