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
 * @filesource ./system/modules/currencies/views/currencies_update_rates_dialog.php
 */
?>

Ext.define('Toc.currencies.CurrenciesUpdateRatesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    this.currenciesId = config.currenciesId;
    
    config.id = 'currencies-update-rates-win';
    config.title = '<?= lang('action_heading_update_rates'); ?>';
    config.iconCls = 'icon-update-exchange-rates';
    config.layout = 'fit';
    config.width = 450;
    config.height = 240;
    config.modal = true;
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?= lang('button_update'); ?>',
        handler: function() {
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
  
  buildForm: function() {
    this.frmUpdateRates = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'currencies',
        action : 'update_currency_rates',
        currencies_id: this.currenciesId
      },
      border: false,
      bodyPadding: 10,
      frame: false,
      fieldDefaults: {
        labelSeparator: ''
      },
      items: [
        {border: false, html: '<p class="form-info"><?= lang('introduction_update_exchange_rates'); ?></p>'},
        {xtype: 'radiofield', name: 'service', boxLabel: 'Oanda (http://www.oanda.com)', inputValue: 'oanda', hideLabel: true, checked: true},
        {xtype: 'radiofield', name: 'service', boxLabel: 'XE (http://www.xe.com)', inputValue: 'xe', hideLabel: true},
        {border: false, html: '<p class="form-info"><?= lang('service_terms_agreement'); ?></p>'}
      ]
    });
    
    return this.frmUpdateRates;
  },
  
  submitForm : function() {
    this.frmUpdateRates.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
      },
      scope: this
    });   
  }
});

/* End of file currencies_update_rates_dialog.php */
/* Location: ./system/modules/currencies/views/currencies_update_rates_dialog.php */