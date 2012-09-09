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

Ext.define('Toc.countries.ZonesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'zones-dialog-win';
    config.title = '<?php echo lang('action_heading_new_zone'); ?>';
    config.layout = 'fit';
    config.width = 500;
    config.height = 150;
    config.modal = true;
    config.iconCls = 'icon-countries-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
          handler: function() {
            this.submitForm();
          },
          scope: this
        },{
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
  
  show: function(countriesId, zId) {
    this.countriesId = countriesId || null;
    var zoneId = zId || null;
    
    this.frmZone.form.baseParams['zone_id'] = zoneId;
    this.frmZone.form.baseParams['countries_id'] = countriesId;
    
    if (zoneId > 0) {
      this.frmZone.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'countries',
          action: 'load_zone'
        },
        success: function(form, action) {
          Toc.countries.ZonesDialog.superclass.show.call(this);
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
    this.frmZone = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'countries',
        action: 'save_zone'
      },
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_zone_name'); ?>', name: 'zone_name', allowBlank: false},
        {xtype: 'textfield', fieldLabel: '<?php echo lang('field_zone_code'); ?>', name: 'zone_code', allowBlank: false}
      ]
    });
    
    return this.frmZone;
  },
  
  submitForm: function() {
    this.frmZone.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      }, scope: this
    });   
  }
});

/* End of file countries_zones_dialog.php */
/* Location: ./system/modules/countries/views/countries_zones_dialog.php */