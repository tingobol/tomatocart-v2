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
 * @filesource ./system/modules/currencies/views/currencies_dialog.php
 */
?>

Ext.define('Toc.currencies.CurrenciesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'currencies-dialog-win';
    config.title = '<?= lang('action_heading_new_currency'); ?>';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-currencies-win';
    
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
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);  
  },
  
  show: function(id) {
    var currenciesId = id || null;
    
    this.frmCurrency.form.baseParams['currencies_id'] = currenciesId;

    if (currenciesId > 0) {
      this.frmCurrency.load({
        url: Toc.CONF.CONN_URL,
        params:{
          action: 'load_currency',
        },
        success: function(form, action) {
          if(action.result.data.is_default == '1') {
            Ext.getCmp('is_default').disable();
          }
          
          Toc.currencies.CurrenciesDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmCurrency = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'currencies',
        action: 'save_currency'
      },
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [
        {xtype: 'textfield', fieldLabel: '<?= lang('field_title'); ?>', name: 'title', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_code'); ?>', name: 'code', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_symbol_left'); ?>', name: 'symbol_left'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_symbol_right'); ?>', name: 'symbol_right'},
        {xtype: 'numberfield', fieldLabel: '<?= lang('field_decimal_places'); ?>', name: 'decimal_places', allowDecimals: false},
        {xtype: 'numberfield', fieldLabel: '<?= lang('field_currency_value'); ?>', name: 'value', decimalPrecision: 10},
        {xtype: 'checkbox', fieldLabel: '<?= lang('field_set_default'); ?>', id: 'is_default', name: 'is_default', anchor: ''}
      ]
    });
    
    return this.frmCurrency;
  },
  
  submitForm : function() {
    this.frmCurrency.form.submit({
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

/* End of file currencies_dialog.php */
/* Location: ./system/modules/currencies/views/currencies_dialog.php */