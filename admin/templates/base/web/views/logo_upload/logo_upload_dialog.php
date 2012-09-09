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
 * @filesource ./system/modules/logo_upload/views/logo_upload_dialog.php
 */
?>

Ext.define('Toc.logo_upload.LogoUploadDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'logo_upload-win';
    config.title = '<?= lang('heading_logo_upload_title'); ?>';
    config.width = 400;
    config.height = 250;
    config.iconCls = 'icon-logo_upload-win';
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: '<?= lang('button_save'); ?>',
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
  
  show: function () {
    Ext.Ajax.request({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'logo_upload',
        action: 'get_logo'
      },
      callback: function(options, success, response) {
        result = Ext.decode(response.responseText);
        
        if (result.success == true) {
          this.pnlImage.update(result.image);
        }
      },
      scope: this
    });

    this.callParent();
  },
  
  buildForm: function() {
    this.frmUpload = Ext.create('Ext.form.Panel', {
      fileUpload: true,
      url: Toc.CONF.CONN_URL,
      layout: 'border',
      border: false,
      baseParams: {  
        module: 'logo_upload',
        action : 'save_logo'
      },
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      },
      items: [
        {
          region: 'north',
          layout: 'anchor',
          border: false,
          bodyPadding: 10,
          items: [
            {xtype: 'filefield', fieldLabel: '<?= lang('field_logo_image'); ?>', name: 'logo_image'}
          ]
        }
      ]
    });
    
    this.pnlImage = Ext.create('Ext.Panel', {
      region: 'center',
      border: false,
      style: 'text-align: center'
    });
    
    this.frmUpload.add(this.pnlImage);
    
    return this.frmUpload;
  },
  
  submitForm : function() {
    this.frmUpload.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
        image = '<img src ="' + action.result.image + '" width="' + action.result.width + '" height="' + action.result.height + '" style="padding: 10px" />';
        this.pnlImage.update(image);
        this.doLayout();
         
        this.fireEvent('savesuccess', action.result.feedback);
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

/* End of file logo_upload_dialog.php */
/* Location: ./system/modules/logo_upload/views/logo_upload_dialog.php */