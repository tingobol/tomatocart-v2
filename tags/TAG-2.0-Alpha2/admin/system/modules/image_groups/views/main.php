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
 * @filesource ./system/modules/image_groups/views/main.php
 */

  echo 'Ext.namespace("Toc.image_groups");';
?>

Ext.override(Toc.desktop.ImageGroupsWindow, {
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('image_groups-win');
     
    if (!win) {
      grd = Ext.create('Toc.image_groups.ImageGroupsGrid');
      
      grd.on('create', function() {this.onCreateImageGroups(grd);}, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('edit', function(record) {this.onEditImageGroups(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'image_groups-win',
        title: '<?= lang('heading_image_groups_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-image_groups-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateImageGroups: function(grd) {
    var dlg = this.createImageGroupsDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditImageGroups: function(grd, record) {
    var dlg = this.createImageGroupsDialog();
    dlg.setTitle(record.get("title"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("id"));
  },
  
  createImageGroupsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('image_groups-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.image_groups.ImageGroupsDialog);
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
/* Location: ./system/modules/image_groups/views/main.php */