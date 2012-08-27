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
 * @filesource
 */

  echo 'Ext.namespace("Toc.manufacturers");';
  
  require_once 'manufacturers_grid.php';
  require_once 'manufacturers_dialog.php';
  require_once 'manufacturers_general_panel.php';
  require_once 'manufacturers_meta_info_panel.php';
?>

Ext.override(Toc.desktop.ManufacturersWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('manufacturers-win');
     
    if (!win) {
      grd = Ext.create('Toc.manufacturers.ManufacturersGrid');
      
      grd.on('create', function() {this.onCreateManufacturer(grd);}, this);
      grd.on('edit', function(record) {this.onEditManufacturer(grd, record);}, this);
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'manufacturers-win',
        title: '<?= lang('heading_manufacturers_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-manufacturers-win',
        layout: 'fit',
        items: grd
      });
    }
           
    win.show();
  },
  
  onCreateManufacturer: function(grd) {
    var dlg = this.createManufacturersDialog();
    dlg.setTitle('<?= lang('heading_new_manufacturers_title'); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },

  createManufacturersDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('manufacturers_dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.manufacturers.ManufacturersDialog);
    }
    
    return dlg;
  },
  
  onEditManufacturer: function(grd, record) {
    var dlg = this.createManufacturersDialog();
    dlg.setTitle(record.get('manufacturers_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('manufacturers_id'));
  },

  onSaveSuccess: function(dlg, grd) {
    dlg.on('savesuccess', function(feedback) {
      this.onShowNotification(feedback);
      
      grd.onRefresh();
    }, this);
  },
//  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  } 
});

/* End of file main.php */
/* Location: ./system/modules/manufacturers/views/main.php */