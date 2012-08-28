<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart
 *
 * An open source application ecommerce framework
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2011, TomatoCart, Inc.
 * @license   http://www.gnu.org/licenses/gpl-3.0.html
 * @link    http://tomatocart.com
 * @since   Version 0.5
 * @filesource orders_delete_confirm_dialog.php
 */
?>

Ext.define('Toc.orders.OrdersDeleteComfirmDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'orders-delete-confirm-dialog-win';
    config.width = 450;
    config.modal = true;
    config.iconCls = 'icon-orders-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnDelete,
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
    
    this.addEvents({'deletesuccess': true});
    
    this.callParent([config]);  
  },
  
  show: function (action, ordersId, orders) {
    this.frmConfirm.baseParams['action'] = action;
    
    if (action == 'delete_order') {
      this.frmConfirm.baseParams['orders_id'] = ordersId; 
      this.setTitle('<?= lang('introduction_delete_order'); ?>');

      html = '<p class="form-info"><?= lang('introduction_delete_order'); ?></p><p class="form-info"><b>#' + orders + '</b></p>';
      this.pnlConfirmInfo.update(html);
    } else {
      this.frmConfirm.baseParams['batch'] = ordersId;
      this.setTitle('<?= lang('introduction_batch_delete_orders'); ?>');
       
      html = '<p class="form-info"><?= lang('introduction_batch_delete_orders'); ?></p><p class="form-info"><b>' + orders + '</b></p>';
      this.pnlConfirmInfo.update(html);
    }
    
    this.doLayout();
    
    this.callParent();
  },
  
  buildForm: function() {
    this.pnlConfirmInfo = Ext.create('Ext.Panel', {border: false});
    
    this.pnlRestockCheckbox = Ext.create('Ext.Panel', {
      border: false,
      cls: 'form-info',
      items: [
        {
          xtype: 'checkboxfield',
          name: 'restock',
          fieldLabel: 'Checkbox',
          hideLabel: true,
          boxLabel: '<?= lang('field_restock_product_quantity'); ?>'
        }
      ]
    });
    
    this.frmConfirm = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'orders'
      },
      bodyPadding: 10,
      border: false,
      items: [this.pnlConfirmInfo, this.pnlRestockCheckbox]
    });
    
    return this.frmConfirm;
  },
  
  submitForm : function() {
    this.frmConfirm.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
        this.fireEvent('deletesuccess', action.result.feedback);
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

/* End of file orders_delete_confirm_dialog.php */
/* Location: ./system/modules/orders/views/orders_delete_confirm_dialog.php */