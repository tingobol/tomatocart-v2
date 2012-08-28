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
 * @filesource modules/articles_categories/views/articles_categories_dialog.php
 */
?>

Ext.define('Toc.articles_categories.ArticlesCategoriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'articles_categories-dialog-win';
    config.title = '<?= lang('action_heading_new_category'); ?>';
    config.layout = 'fit';
    config.width = 440;
    config.height = 380;
    config.modal = true;
    config.iconCls = 'icon-articles_categories-win';
    
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
    
    this.callParent([config]); 
  },
  
  show: function(id) {
    var categoriesId = id || null;
    
    this.frmArticlesCategory.form.baseParams['articles_categories_id'] = categoriesId;
    
    if (categoriesId > 0) {
      this.frmArticlesCategory.load({
        url: Toc.CONF.CONN_URL,
        params:{
          action: 'load_articles_categories'
        },
        success: function(form, action) {
          Toc.articles_categories.ArticlesCategoriesDialog.superclass.show.call(this);
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
    this.pnlGeneral = Ext.create('Toc.articles_categories.GeneralPanel');
    this.pnlMetaInfo = Ext.create('Toc.articles_categories.MetaInfoPanel');
    
    var tabArticlesCategories = Ext.create('Ext.tab.Panel', {
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
    
    this.frmArticlesCategory = Ext.create('Ext.form.Panel', {
      layout: 'fit',
      fileUpload: true,
      fieldDefaults: {
        labelWidth: 120,
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'articles_categories',
        action: 'save_articles_category'
      },
      items: tabArticlesCategories
    });
    
    return this.frmArticlesCategory;
  },
  
  submitForm : function() {
    this.frmArticlesCategory.form.submit({
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

/* End of file articles_categories_dialog.php */
/* Location: ./system/modules/articles_categories/aricles_categories_dialog.php */