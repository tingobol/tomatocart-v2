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
 * @filesource ./system/modules/orders_status/views/orders_status_dialog.php
 */
?>

Ext.define('Toc.orders_status.OrdersStatusDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'orders_status-dialog-win';
    config.title = '<?php echo lang('action_heading_new_order_status'); ?>';
    config.layout = 'fit';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-orders_status-win';
    
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
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var ordersStatusId = id || null;      
    
    if (ordersStatusId > 0) {
      this.frmOrdersStatus.form.baseParams['orders_status_id'] = ordersStatusId;
      
      this.frmOrdersStatus.load({
        url: Toc.CONF.CONN_URL,
        params: {
          action: 'load_orders_status',
          orders_status_id: ordersStatusId
        },
        success: function(form, action) {
          if (action.result.data['default'] == '1') {
            this.chkDefault.disable();
          }
          
          Toc.orders_status.OrdersStatusDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this       
      });
    } else {   
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmOrdersStatus = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'orders_status',
        action: 'save_orders_status'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: '',
        labelWidth: 150
      }
    });
    
    <?php
      $i = 1;
      
      foreach(lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name'); ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmOrdersStatus.add(txtLang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    this.chkDefault = Ext.create('Ext.form.Checkbox', {
      name: 'default',
      fieldLabel: '<?php echo lang('field_set_as_default'); ?>'
    });
    
    this.frmOrdersStatus.add(this.chkDefault);
    
    this.frmOrdersStatus.add({xtype: 'checkbox', name: 'public_flag', fieldLabel: '<?php echo lang('field_public_flag'); ?>'});
    
    return this.frmOrdersStatus;
  },
  
  submitForm: function() {
    this.frmOrdersStatus.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
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

/* End of file orders_status_dialog.php */
/* Location: ./system/modules/orders_status/views/orders_status_dialog.php */