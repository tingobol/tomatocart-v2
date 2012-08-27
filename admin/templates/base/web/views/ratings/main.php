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
 * @filesource ./system/modules/ratings/views/main.php
 */

  echo 'Ext.namespace("Toc.ratings");';
  
  require_once 'ratings_dialog.php';
  require_once 'ratings_grid.php';
?>

Ext.override(Toc.desktop.RatingsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('ratings-win');
     
    if(!win){
      var grd = Ext.create(Toc.ratings.RatingsGrid);
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateRatings(grd);}, this);
      grd.on('edit', function(record) {this.onEditRatings(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'ratings-win',
        title: '<?= lang('heading_title_ratings'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-ratings-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateRatings: function(grd) {
    var dlg = this.createRatingsDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditRatings: function(grd, record) {
    var dlg = this.createRatingsDialog();
    dlg.setTitle(record.get('ratings_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('ratings_id'));
  },
  
  createRatingsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('ratings-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.ratings.RatingsDialog);
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


