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

  echo 'Ext.namespace("Toc.currencies");';
  
  require_once 'currencies_grid.php';
  require_once 'currencies_dialog.php';
  require_once 'currencies_update_rates_dialog.php';
?>

Ext.override(Toc.desktop.CurrenciesWindow, {
  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('currencies-win');
     
    if(!win){
      var grd = Ext.create('Toc.currencies.CurrenciesGrid');
      
      grd.on('create', function() {this.onCreateCurrency(grd);}, this);
      grd.on('edit', function(record) {this.onEditCurrency(grd, record);}, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'currencies-win',
        title: '<?= lang("heading_currencies_title"); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-currencies-win',
        layout: 'fit',
        items: grd
      });
    }

    win.show();
  },
  
  onCreateCurrency: function(grd) {
    dlg = this.createCurrenciesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditCurrency: function(grd, record) {
    var dlg = this.createCurrenciesDialog();
    dlg.setTitle(record.get("title"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get("currencies_id"));
  },
  
  createCurrenciesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('currencies-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.currencies.CurrenciesDialog);
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
/* Location: ./system/modules/currencies/views/main.php */