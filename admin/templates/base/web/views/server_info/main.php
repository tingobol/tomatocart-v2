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
 * @filesource ./system/modules/server_info/views/main.php
 */

  echo 'Ext.namespace("Toc.server_info");';
  
  require_once 'server_info_dialog.php';
?>

Ext.override(Toc.desktop.ServerInfoWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('server_info-win');
     
    if(!win){
      win = desktop.createWindow({},Toc.server_info.ServerInfoDialog);
    }

    win.show();
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
/* Location: ./system/modules/server_info/views/main.php */