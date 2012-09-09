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
 * @filesource modules/articles/views/main.php
 */

  echo 'Ext.namespace("Toc.articles");';
?>

Ext.override(Toc.desktop.ArticlesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('articles-win');
     
    if(!win){
      grd = Ext.create('Toc.articles.ArticlesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateArticles(grd);}, this);
      grd.on('edit', function(record) {this.onEditArticles(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'articles-win',
        title: '<?php echo lang('heading_articles_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-articles-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateArticles: function(grd) {
    var dlg = this.createArticlesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditArticles: function(grd, record) {
    var dlg = this.createArticlesDialog();
    dlg.setTitle(record.get("articles_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("articles_id"));
  },
  
  createArticlesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('articles-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.articles.ArticlesDialog);
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
/* Location: ./system/modules/articles/views/main.php */