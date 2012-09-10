<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

  echo 'Ext.namespace("Toc.ratings");';
  
  include 'ratings_dialog.php';
  include 'ratings_grid.php';
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
        title: '<?php echo lang('heading_title_ratings'); ?>',
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

/* End of file main.php */
/* Location: ./templates/base/web/views/ratings/main.php */