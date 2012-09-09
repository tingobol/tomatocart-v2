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

Ext.define('Toc.countries.CountriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'countries-dialog-win';
    config.title = '<?php echo lang('action_heading_new_country'); ?>';
    config.width = 450;
    config.modal = true;
    config.iconCls = 'icon-countries-win';
    
    config.items =this.buildForm();
    
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
          scope:this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);  
  },
  
  show: function(id) {
    var countriesId = id || null;
    
    this.frmCountry.form.baseParams['countries_id'] = countriesId;
    
     if (countriesId > 0) {
      this.frmCountry.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'countries',
          action: 'load_country'
        },
        success: function(form, action){
          Toc.countries.CountriesDialog.superclass.show.call(this);
        },
        failure: function(form, action){
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this  
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmCountry = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'countries',
        action: 'save_country'
      },
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [
        {xtype: 'hidden',name: 'id'},
        {xtype: 'textfield',fieldLabel: '<?php echo lang('field_name'); ?>', name: 'countries_name', allowBlank: false},
        {xtype: 'textfield',fieldLabel: '<?php echo lang('field_iso_code_2'); ?>', name: 'countries_iso_code_2', allowBlank: false},
        {xtype: 'textfield',fieldLabel: '<?php echo lang('field_iso_code_3'); ?>', name: 'countries_iso_code_3', allowBlank: false},
        {xtype: 'textarea',fieldLabel: '<?php echo lang('field_address_format'); ?>', name: 'address_format'}
      ]
    });
    
    return this.frmCountry;
  },
  
  submitForm : function() {
    this.frmCountry.form.submit({
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

/* End of file countries_dialog.php */
/* Location: ./system/modules/countries/views/countries_dialog.php */