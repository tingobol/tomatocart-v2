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
 * @filesource .system/modules/customers_groups/views/main.php
 */

  echo 'Ext.namespace("Toc.customers_groups");';
?>

Ext.override(Toc.desktop.CustomersGroupsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('customers_groups-win');
     
    if(!win){
      grd = Ext.create('Toc.customers_groups.CustomersGroupsGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateCustomersGroups(grd);}, this);
      grd.on('edit', function(record) {this.onEditCustomersGroups(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'articles-win',
        title: '<?= lang('heading_customers_groups_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-customers_groups-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateCustomersGroups: function(grd) {
    var dlg = this.createCustomersGroupsDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditCustomersGroups: function(grd, record) {
    var dlg = this.createCustomersGroupsDialog();
    dlg.setTitle(record.get("customers_groups_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("customers_groups_id"));
  },
  
  createCustomersGroupsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('customers_groups-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.customers_groups.CustomersGroupsDialog);
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
/* Location: ./system/modules/customers_groups/views/main.php */