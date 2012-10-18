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
 * @filesource ./system/modules/feature_products_manager/views/main.php
 */

  echo 'Ext.namespace("Toc.feature_products_manager");';
?>

Ext.override(Toc.desktop.FeatureProductsManagerWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('feature_products_manager-win');
     
    if(!win){
      var grd = Ext.create('Toc.feature_products_manager.ProductsManagerGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'feature_products_manager-win',
        title: '<?= lang('heading_feature_products_manager_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-feature_products_manager-win',
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
/* Location: ./system/modules/feature_products_manager/views/main.php */