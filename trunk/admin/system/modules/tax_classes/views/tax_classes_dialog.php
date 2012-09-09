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

Ext.define('Toc.tax_classes.TaxClassesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'tax-class-dialog-win';
    config.title = '<?php echo lang('action_heading_new_tax_class'); ?>';
    config.width = 500;
    config.modal = true;
    config.iconCls = 'icon-tax_classes-win';
    
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
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);  
  },
  
  show: function (id) {
    var taxClassesId = id || null;
    
    this.frmTaxClass.form.baseParams['tax_class_id'] = taxClassesId;

    if (taxClassesId > 0) {
      this.frmTaxClass.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'tax_classes',
          action: 'load_tax_class'
        },
        success: function() {
          Toc.tax_classes.TaxClassesDialog.superclass.show.call(this);
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
    this.frmTaxClass = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'tax_classes',
        action: 'save_tax_class'
      },
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 10,
      items: [                           
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_title'); ?>', 
          name: 'tax_class_title', 
          allowBlank: false
        },
        {
          xtype: 'textfield', 
          fieldLabel: '<?php echo lang('field_description'); ?>', 
          name: 'tax_class_description'
        }
      ]
    });
    
    return this.frmTaxClass;
  },
  
  submitForm: function() {
    this.frmTaxClass.form.submit({
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

/* End of file tax_classes_dialog.php */
/* Location: ./system/modules/tax_classes/tax_classes_dialog.php */