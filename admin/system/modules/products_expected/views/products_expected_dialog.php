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
 * @filesource .system/modules/products_expected/views/products_expected_dialog.php
 */
?>

Ext.define('Toc.products_expected.ProductsExpectedDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'products_expected-dialog-win';
    config.title = '<?php echo lang("table_heading_date_expected"); ?>';
    config.layout = 'fit';
    config.width = 400;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-products_expected-win';
    
    config.items = this.buildForm();
    
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
  
  show: function (id) {
    var productsId = id || null;
    
    this.frmProductsExpected.form.baseParams['products_id'] = productsId;
    
    this.frmProductsExpected.load({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'products_expected',
        action: 'load_products_expected'
      },
      success: function (form, action) {
        Toc.products_expected.ProductsExpectedDialog.superclass.show.call(this);
      },
      failure: function (form, action) {
        Ext.MessageBox.alert( TocLanguage.msgErrTitle, action.result.feedback );
      },
      scope: this
    });
  },
  
  buildForm: function() {
    this.frmProductsExpected = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'products_expected',
        action: 'save_products_expected'
      },
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      },
      border: false,
      bodyPadding: 10,
      items: [
        {
          xtype: 'datefield',
          fieldLabel: '<?php echo lang("table_heading_date_expected"); ?>',
          name: 'products_date_available',
          format: 'Y-m-d'
        }
      ]
    });
    
    return this.frmProductsExpected;
  },
  
  submitForm: function () {
    this.frmProductsExpected.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert( TocLanguage.msgErrTitle, action.result.feedback );
        }
      },
      scope: this
    });
  }
});

/* End of file products_expected_dialog.php */
/* Location: ./system/modules/products_expected/views/products_expected_dialog.php */