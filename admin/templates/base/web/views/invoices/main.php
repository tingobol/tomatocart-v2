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
 * @filesource system/modules/invoices/views/main.php
 */

  echo 'Ext.namespace("Toc.invoices");';
  
  require_once 'invoices_grid.php';
  require_once 'invoices_dialog.php';
  require_once 'invoices_status_panel.php';
  require_once 'templates/base/web/views/orders/orders_products_grid.php';
  require_once 'templates/base/web/views/orders/orders_transaction_grid.php';
?>

Ext.override(Toc.desktop.InvoicesWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('invoices-win');
     
    if (!win) {
      var grd = Ext.create('Toc.invoices.InvoicesGrid');
      
      grd.on('view', this.onView, this);
      
      win = desktop.createWindow({
        id: 'invoices-win',
        title: '<?= lang('heading_invoices_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-invoices-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onView: function(record) {
    var dlg = this.createInvoicesDialog({ordersId: record.get('orders_id')});
    
    dlg.setTitle(record.get('invoices_number') + ': ' + record.get('customers_name'));
    
    dlg.show();
  },
  
  createInvoicesDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('invoices-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.invoices.InvoicesDialog);
    }
    
    return dlg;
  }
});

/* End of file main.php */
/* Location: system/modules/invoices/views/main.php */
