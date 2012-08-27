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

Ext.define('Toc.manufacturers.ManufacturersDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'manufacturers_dialog-win';
    config.title = '<?= lang('action_heading_new_manufacturer'); ?>';
    config.width = 500;
    config.height = 380;
    config.modal = true;
    config.layout = 'fit';
    config.iconCls = 'icon-manufacturers-win';
    
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
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);  
  },
  
  show: function(id) {
    var manufacturersId = id || null;
    
    this.frmManufacturer.baseParams['manufacturers_id'] = manufacturersId;
    
    if (manufacturersId > 0) {
      this.frmManufacturer.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'manufacturers',
          action: 'load_manufacturer'
        },
        success: function(form, action) {
          var img = action.result.data.manufacturers_image;
          
          if (img) {
            var html = '<img src ="<?= IMGHTTPPATH; ?>manufacturers/' + img + '"  style = "margin-left: 110px; width: 80px; height: 80px" /><br/><span style = "padding-left: 110px;">/images/manufacturers/' + img + '</span>';
            this.pnlGeneral.getComponent('manufactuerer_image_panel').update(html);
          }          
          
          Toc.manufacturers.ManufacturersDialog.superclass.show.call(this);
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
    this.pnlGeneral = Ext.create('Toc.manufacturers.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.manufacturers.MetaInfoPanel');
    
    var tabManufacturers = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      border: false,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false,
      items: [
        this.pnlGeneral,
        this.pnlMetaInfo
      ]
    });
    
    this.frmManufacturer = Ext.create('Ext.form.Panel', {
      id: 'form-manufacturers',
      layout: 'fit',
      border: false,
      fileUpload: true,
      fieldDefaults: {
        labelSeparator: '',
        labelWidth: 100,
        anchor: '97%'
      },
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'manufacturers',
        action: 'save_manufacturer'
      },
      items: tabManufacturers
    });
    
    return this.frmManufacturer;
  },
  
  submitForm : function() {
    this.frmManufacturer.form.submit({
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

/* End of file manufacturers_dialog.php */
/* Location: ./system/modules/manufacturers/views/manufacturers_dialog.php */