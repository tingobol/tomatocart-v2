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
 * @filesource system/modules/languages/views/languages_upload_dialog.php
 */
?>

Ext.define('Toc.languages.LanguagesUploadDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
  
    config.id = 'languages-upload-dialog-win';
    config.title = '<?php echo lang('action_heading_upload_language'); ?>';
    config.width = 400;
    config.height = 200;
    config.modal = true;
    config.iconCls = 'icon-languages-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnUpload,
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    this.frmLanguage = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      fileUpload: true,
      baseParams: {  
        module: 'languages',
        action: 'upload_language'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 100,
        anchor: '98%'
      },
      items: [
        {
          xtype: 'filefield', 
          layout: 'anchor', 
          fieldLabel: '<?php echo lang('field_language_zip_file'); ?>', 
          name: 'file'
        },
        {
          xtype: 'displayfield',
          border: false,
          style: 'padding: 16px 0;',
          hideLabel: true,
          value:'<?php echo lang('introduction_upload_language'); ?>'
        }
      ]
    });
    
    return this.frmLanguage;
  },
  
  submitForm : function() {
    this.frmLanguage.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action) {
         this.close();
         window.location.reload();  
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

/* End of file languages_upload_dialog.php */
/* Location: system/modules/languages/views/languages_upload_dialog.php */

