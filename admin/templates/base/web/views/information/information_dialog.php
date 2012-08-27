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
 * @filesource .system/modules/information/views/information_dialog.php
 */
?>

Ext.define('Toc.information.InformationDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'information-dialog-win';
    config.title = '<?= lang('action_heading_new_information'); ?>';
    config.layout = 'fit';
    config.width = 850;
    config.height = 530;
    config.modal = true;
    config.iconCls = 'icon-information-win';
    
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
    
    this.addEvents({'savesuccess' : true})
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var articlesId = id || null;
    
    this.frmArticle.form.baseParams['articles_id'] = articlesId;
    
    if(articlesId > 0) {
      this.frmArticle.load({
        url: Toc.CONF.CONN_URL,
        params:{
          action: 'load_article',
          articles_id: articlesId
        },
        success: function(form, action) {
          Toc.information.InformationDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        }, 
        scope: this
      });
    }
    else
    {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmArticle = Ext.create('Ext.form.Panel', {
      fileUpload: true,
      layout: 'border',
      border: false,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      title:'<?= lang('heading_title_data'); ?>',
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'information',
        action: 'save_article'
      },
      deferredRender: false,
      items: [this.getContentPanel(), this.getDataPanel()]
    });
    
    return this.frmArticle;
  },
  
  getDataPanel: function() {
    this.pnlData = Ext.create('Ext.Panel', {
      layout: 'column',
      region: 'north',
      border: false,
      bodyPadding: 6,
      items: [
        {
          layout: 'anchor',
          border: false,
          columnWidth: .7,
          items: [
            {
              layout: 'column',
              border: false,
              items: [
                {
                  border: false,
                  width: 200,
                  items: [
                    {
                      fieldLabel: '<?= lang('field_publish'); ?>', 
                      xtype:'radio', 
                      name: 'articles_status',
                      inputValue: '1',
                      checked: true,
                      boxLabel: '<?= lang('field_publish_yes'); ?>'
                    }
                  ]
                },
                {
                  border: false,
                  width: 200,
                  items: [
                    {
                      hideLabel: true,
                      xtype:'radio', 
                      name: 'articles_status',
                      inputValue: '0',
                      boxLabel: '<?= lang('field_publish_no'); ?>'
                    }
                  ]
                }
              ]
            },
            {xtype:'numberfield', fieldLabel: '<?= lang('field_order'); ?>', name: 'articles_order', id: 'articles_order'}
          ]
        }
      ]
    });
    
    return this.pnlData;
  },
  
  getContentPanel: function() {
    this.pnlGeneral = Ext.create('Toc.information.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.information.MetaInfoPanel');
    
    var tabInformation = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      region: 'center',
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
    
    return tabInformation;
  },
  
  submitForm : function() {
    this.frmArticle.form.submit({
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

/* End of file information_dialog.php */
/* Location: ./system/modules/information/views/information_dialog.php */
