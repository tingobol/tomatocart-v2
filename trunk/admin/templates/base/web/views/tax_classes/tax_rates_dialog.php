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

Ext.define('Toc.tax_classes.TaxRatesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'tax-rate-dialog-win';
    config.title = '<?= lang('action_heading_new_tax_rate'); ?>';
    config.width = 500;
    config.modal = true;
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (taxClassId, ratesId) {
    this.taxClassId = taxClassId || null;
    var taxRatesId = ratesId || null; 
    
    this.frmTaxRate.form.baseParams['tax_class_id'] = this.taxClassId;
    this.frmTaxRate.form.baseParams['tax_rates_id'] = taxRatesId;

    if (taxRatesId > 0) {
      this.frmTaxRate.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'tax_classes',
          action: 'load_tax_rate',
          tax_rates_id: taxRatesId
        },
        success: function(form, action) {
          Toc.tax_classes.TaxRatesDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData)
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    var dsGeoZone = Ext.create('Ext.data.Store', {
      fields: ['geo_zone_id', 'geo_zone_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'tax_classes',
          action: 'list_geo_zones'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cobZoneGroup = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?= lang('field_tax_rate_zone_group'); ?>',
      store: dsGeoZone, 
      name: 'geo_zone_id', 
      queryMode: 'local',
      displayField: 'geo_zone_name', 
      valueField: 'geo_zone_id', 
      editable: false,
      forceSelection: true    
    });
    
    this.frmTaxRate = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'tax_classes',
        action: 'save_tax_rate'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      items: [
        this.cobZoneGroup,
        {xtype: 'numberfield', fieldLabel: '<?= lang('field_tax_rate'); ?>', name: 'tax_rate', decimalPrecision: 4, width:300},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_tax_rate_description'); ?>', name: 'tax_description', width:300},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_tax_rate_priority'); ?>', name: 'tax_priority', width:300}
      ]
    });
    
    return this.frmTaxRate;
  },
  
  submitForm: function() {
    this.frmTaxRate.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success:function(form, action) {
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


/* End of file tax_rates_dialog.php */
/* Location: ./system/modules/tax_classes/tax_rates_dialog.php */