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
 * @filesource ./system/modules/images/views/main.php
 */
  
  echo 'Ext.namespace("Toc.images");';
  
  require_once 'images_grid.php';
  require_once 'images_resize_dialog.php';
  require_once 'images_check_dialog.php';
?>

Ext.override(Toc.desktop.ImagesWindow, {
 
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('images-win');
     
    if(!win){
      var grd = Ext.create('Toc.images.ImagesGrid');
      
      grd.on('checkimages', function(record) {this.onCheckImages(record);}, this);
      grd.on('resizeimages', function(record) {this.onResizeImages(record);}, this);
      
      win = desktop.createWindow({
        id: 'images-win',
        title: '<?= lang('heading_images_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-images-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCheckImages: function(record) {
    var dlg = this.createImagesCheckDialog();
    dlg.setTitle(record.get('module'));
    
    dlg.show();
  },
  
  onResizeImages: function(record) {
    var dlg = this.createImagesResizeDialog();
    dlg.setTitle(record.get('module'));
    
    dlg.show();
  },
    
  createImagesCheckDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('images-check-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.images.ImagesCheckDialog);
    }
    
    return dlg;
  },
  
  createImagesResizeDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('images-resize-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.images.ImagesResizeDialog);
    }
    
    return dlg;
  }
});

/* End of file main.php */
/* Location: ./system/modules/images/views/main.php */
