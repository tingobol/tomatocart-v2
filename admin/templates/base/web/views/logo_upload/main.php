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
 * @filesource ./system/modules/logo_upload/views/main.php
 */

  echo 'Ext.namespace("Toc.logo_upload");';
  
  require_once 'logo_upload_dialog.php';
?>

Ext.override(Toc.desktop.LogoUploadWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('logo_upload-win');
    
    if (!win) {
      win = desktop.createWindow({}, Toc.logo_upload.LogoUploadDialog);
      
      win.on('savesuccess', this.onShowNotification, this);
    }
    
    win.show();
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }  
});

/* End of file main.php */
/* Location: ./system/modules/logo_upload/views/main.php */