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
 * @filesource ./system/modules/homepage_info/views/main.php
 */

  echo 'Ext.namespace("Toc.homepage_info");';
?>

Ext.override(Toc.desktop.HomepageInfoWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('homepage_info-win');
    
    if (!win) {
      win = desktop.createWindow({}, Toc.homepage_info.HomepageInfoDialog);
      
      win.on('savesuccess', function(feedback) {
        this.app.showNotification({
          title: TocLanguage.msgSuccessTitle,
          html: feedback
        });
      }, this);
    }
    
    win.show();
  }
});



/* End of file homepage_info.php */
/* Location: ./system/modules/homepage_info/views/main.php */