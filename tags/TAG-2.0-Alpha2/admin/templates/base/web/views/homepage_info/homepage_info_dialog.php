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
 * @filesource ./system/modules/homepage_info/views/homepage_info_dialog.php
 */
?>

Ext.define('Toc.homepage_info.HomepageInfoDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('heading_homepage_info_title'); ?>';
    config.layout = 'fit';
    config.width = 870;
    config.height = 450;
    config.iconCls = 'icon-homepage_info-win';
    config.border = false;
    
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
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function() {
    this.frmPagehomeInfo.load({
      url: Toc.CONF.CONN_URL,
      params: {
        module: 'homepage_info',
        action: 'load_info'
      }
    }, this);
    
    this.callParent();
  },
  
  buildForm: function() {
    var pnlMetaInfo = Ext.create('Toc.homepage_info.MetaInfoPanel');
    var pnlHomepageInfo = Ext.create('Toc.homepage_info.HomepageInfoPanel');
    
    var tabProduct = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      defaults:{
        hideMode:'offsets'
      },
      deferredRender: false,
      items: [
        pnlHomepageInfo,
        pnlMetaInfo
      ]
    });
    
    this.frmPagehomeInfo = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      layout: 'fit',
      labelWidth: 120,
      border: false,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '98%'
      },
      baseParams: {  
        module: 'homepage_info',
        action : 'save_info'
      },
      items: tabProduct
    });
    
    return this.frmPagehomeInfo;
  },
  
  submitForm: function() {
    this.frmPagehomeInfo.form.submit({
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

/* End of file homepage_info_dialog.php */
/* Location: ./system/modules/homepage_info/views/homepage_info_dialog.php */