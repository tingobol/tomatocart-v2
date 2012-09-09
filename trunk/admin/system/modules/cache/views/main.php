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
 * @filesource ./system/modules/cache/views/main.php
 */
  
  echo 'Ext.namespace("Toc.cache");';
?>

Ext.override(Toc.desktop.CacheWindow, {
  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('cache-win');
     
    if(!win){
      var grd = Ext.create('Toc.cache.CacheGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'cache-win',
        title: '<?php echo lang('heading_cache_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-cache-win',
        layout: 'fit',
        items: grd
      });
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
/* Location: ./system/modules/cache/views/main.php */
