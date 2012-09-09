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
 * @filesource main.php
 */

  echo 'Ext.namespace("Toc.customers");';  
?>

Ext.override(Toc.desktop.CustomersWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('customers-win');

    if (!win) {                               
      this.pnl = Ext.create('Toc.customers.mainPanel');
      
      this.pnl.on('createcustomer', this.onCreateCustomer, this);
      this.pnl.on('editcustomer', this.onEditCustomer, this);
      this.pnl.on('createaddress', this.onCreateAddress, this);
      this.pnl.on('editaddress', this.onEditAddress, this);
      this.pnl.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'customers-win',
        title: '<?php echo lang('heading_customers_title'); ?>',
        width: 850,
        height: 400,
        iconCls: 'icon-customers-win',
        layout: 'fit',
        items: this.pnl
      });
    }   
    
    win.show();
  },
  
  onCreateCustomer: function() {
    var dlg = this.createCustomerDialog();
    
    dlg.setTitle('<?php echo lang('action_heading_new_customer'); ?>');
    dlg.show();
  },
  
  onEditCustomer: function(rec) {
    var dlg = this.createCustomerDialog();
    
    alert(rec.get('customers_id'));
    
    dlg.setTitle(rec.get('customers_lastname'));
    dlg.show(rec.get('customers_id'));
  },
  
  onCreateAddress: function(customersId) {
    var dlg = this.createAddressBookDialog();
    
    dlg.show(customersId);
  },
  
  onEditAddress: function(customersId, addressId, customer) {
    var dlg = this.createAddressBookDialog();
    
    dlg.setTitle(customer);
    
    dlg.show(customersId, addressId); 
  },
  
  createCustomerDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('customers-dialog-win');    

    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.customers.CustomersDialog);             
      
      dlg.on('savesuccess', function(feedback) {
        this.pnl.grdCustomers.onRefresh();
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;    
  },
  
  createAddressBookDialog : function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('address-book-dialog-win');

    if (!dlg) {
      dlg = desktop.createWindow({},Toc.customers.AddressBookDialog);

      dlg.on('savesuccess', function(feedback) {
        this.pnl.pnlAccordion.grdAddressBook.onRefresh();
        this.app.showNotification({title: TocLanguage.msgSuccessTitle, html: feedback});
      }, this);
    }
    
    return dlg;
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification( {title: TocLanguage.msgSuccessTitle, html: feedback} );
  }
});