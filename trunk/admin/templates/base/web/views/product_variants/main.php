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
 * @filesource ./system/modules/product_variants/views/main.php
 */

  echo 'Ext.namespace("Toc.product_variants");';
  
  require_once 'product_variants_entries_dialog.php';
  require_once 'product_variants_entries_grid.php';
  require_once 'product_variants_groups_dialog.php';
  require_once 'product_variants_groups_grid.php';
  require_once 'product_variants_main_panel.php';
?>

Ext.override(Toc.desktop.ProductVariantsWindow, {
  createWindow: function () {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('product_variants-win');
    
    if (!win) {
      var pnl = Ext.create('Toc.product_variants.MainPanel');
      
      pnl.grdVariantsGroups.on('notifysuccess', this.onShowNotification, this);
      pnl.grdVariantsGroups.on('create', function() {this.onCreateVariantsGroups(pnl.grdVariantsGroups);}, this);
      pnl.grdVariantsGroups.on('edit', function(record) {this.onEditVariantsGroups(pnl.grdVariantsGroups, record);}, this);
      
      pnl.grdVariantsEntries.on('notifysuccess', this.onShowNotification, this);
      pnl.grdVariantsEntries.on('create', function() {this.onCreateVariantsEntries(pnl.grdVariantsEntries);}, this);
      pnl.grdVariantsEntries.on('edit', function(record) {this.onEditVariantsEntries(pnl.grdVariantsEntries, record);}, this);
      
      win = desktop.createWindow({
        id: 'product_variants-win',
        title: '<?= lang("heading_product_variants_title"); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-product_variants-win',
        layout: 'fit',
        items: pnl
      });
    }
    
    win.show();
  },
  
  onCreateVariantsGroups: function(grdGroups) {
    var dlg = this.createProductVariantsGroupsDialog();
    
    this.onSaveSuccess(dlg, grdGroups);
    
    dlg.show();
  },
  
  onCreateVariantsEntries: function(grdEntries) {
    if (grdEntries.variantsGroupsId) {
      var dlg = this.createProductVariantsEntriesDialog();
      
      this.onSaveSuccess(dlg, grdEntries);
      
      dlg.show(grdEntries.variantsGroupsId);
    }else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onEditVariantsGroups: function(grdGroups, record) {
    var dlg = this.createProductVariantsGroupsDialog();
    dlg.setTitle('<?= lang("action_heading_new_variant_group"); ?>');
    
    this.onSaveSuccess(dlg, grdGroups);
    
    dlg.show(record.get('products_variants_groups_id'));
  },
  
  onEditVariantsEntries: function(grdEntries, record) {
    var variantsValuesId = record.get('products_variants_values_id');
    var dlg = this.createProductVariantsEntriesDialog();
    
    dlg.setTitle(grdEntries.variantsGroupsName);
    
    this.onSaveSuccess(dlg, grdEntries);
    
    dlg.show(grdEntries.variantsGroupsId, variantsValuesId);
  },
  
  createProductVariantsGroupsDialog: function () {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('product_variants_groups-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.product_variants.ProductVariantsGroupsDialog);
    }
    
    return dlg;
  },
  
  createProductVariantsEntriesDialog: function () {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('product_variants_entries-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.product_variants.ProductVariantsEntriesDialog);
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
/* Location: ./system/modules/product_variants/views/main.php */
