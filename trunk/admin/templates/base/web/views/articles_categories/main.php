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
 * @filesource modules/articles_categories/views/main.php
 */

    echo 'Ext.namespace("Toc.articles_categories");';

    require_once('articles_categories_dialog.php');
    require_once('articles_categories_grid.php');
    require_once('articles_categories_general_panel.php');
    require_once('articles_categories_meta_info_panel.php');
?>

Ext.override(Toc.desktop.ArticlesCategoriesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('articles_categories-win');
     
    if(!win){
      grd = Ext.create('Toc.articles_categories.ArticlesCategoriesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateArticlesCategory(grd);}, this);
      grd.on('edit', function(record) {this.onEditArticlesCategory(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'articles_categories-win',
        title: '<?= lang('heading_articles_categories_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-articles_categories-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateArticlesCategory: function(grd) {
    var dlg = this.createArticleCategoriesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditArticlesCategory: function(grd, record) {
    var dlg = this.createArticleCategoriesDialog();
    dlg.setTitle(record.get("articles_categories_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("articles_categories_id"));
  },
  
  createArticleCategoriesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('articles_categories-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.articles_categories.ArticlesCategoriesDialog);
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
/* Location: ./system/modules/articles_categories/views/main.php */
