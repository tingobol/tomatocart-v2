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
 * @filesource ./system/modules/languages/views/main.php
 */

  echo 'Ext.namespace("Toc.languages");';
?>

Ext.override(Toc.desktop.LanguagesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('languages-win');
     
    if(!win){
      var grd = Ext.create('Toc.languages.LanguagesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('import', function() {this.onAddLanguages(grd);}, this);
      grd.on('edit', function(record) {this.onEditLanguages(grd, record);}, this);
      grd.on('export', function(record) {this.onExportLanguages(grd, record);}, this);
      grd.on('translations', function(record) {this.onTranslations(grd, record);}, this);
      grd.on('upload', function() {this.onUpload(grd);}, this);
      
      win = desktop.createWindow({
        id: 'articles-win',
        title: '<?php echo lang('heading_languages_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-languages-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onAddLanguages: function(grd) {
    var dlg = this.createLanguagesAddDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditLanguages: function(grd, record) {
    var dlg = this.createLanguagesEditDialog();
    
    dlg.setTitle(record.get('languages_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('languages_id'));
  },
  
  onExportLanguages: function(grd, record) {
    var dlg = this.createLanguagesExportDialog();
    dlg.setTitle(record.get('languages_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('languages_id'));
  },
  
  onTranslations: function(grd, record) {
    var dlg = this.createTranslationsDialog({languagesId: record.get('languages_id')});
    
    dlg.grdTranslations.on('edit', function(params) {this.onEditTranslations(dlg.grdTranslations, params);}, this);
    dlg.grdTranslations.on('notifysuccess', this.onShowNotification, this);
    dlg.grdTranslations.on('adddefinition', function(params) {this.onAddDefinition(dlg.grdTranslations, params);}, this);
    
    dlg.setTitle(record.get('languages_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onAddDefinition: function(grdTranslations, params) {
     var dlg = this.createTranslationAddDialog();
     
     this.onSaveSuccess(dlg, grdTranslations);
     
     dlg.show(params.languagesId, params.group);
  },
  
  onEditTranslations: function(grdTranslations, params) {
    var dlg = this.createTranslationEditDialog({
      languagesId: params.languagesId,
      group: params.group,
      definitionKey: params.record.get('definition_key'),
      definitionValue: params.record.get('definition_value')
    });
    
    this.onSaveSuccess(dlg, grdTranslations);
    
    dlg.show();
  },
  
  onUpload: function(grd) {
    var dlg = this.createLanguagesUploadDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  createLanguagesAddDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('languages-add-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.languages.LanguagesAddDialog);
    }
    
    return dlg;
  },
    
  createLanguagesUploadDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('languages-upload-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.languages.LanguagesUploadDialog);
    }
      
    return dlg;
  },
  
  createLanguagesEditDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('languages-edit-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.languages.LanguagesEditDialog);
    }
      
    return dlg;
  },
  
  createLanguagesExportDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('languages-export-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.languages.LanguagesExportDialog);
    }
      
    return dlg;
  },
  
  createTranslationsDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('translations-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.languages.TranslationsEditDialog);
    }
      
    return dlg;
  },
  
  createTranslationEditDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('translation-edit-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.languages.TranslationEditDialog);
    }
      
    return dlg;
  },
  
  createTranslationAddDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('translation-add-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.languages.TranslationAddDialog);
    }
      
    return dlg;
  },
  
  onSaveSuccess: function(dlg, grd) {
    dlg.on('savesuccess', function(feedback) {
      this.onShowNotification(feedback);
      
      grd.onRefresh();
    }, this);
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./system/modules/languages/views/main.php */