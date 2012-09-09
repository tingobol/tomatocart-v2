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
 * @filesource ./system/modules/administrators/views/main.php
 */

  echo 'Ext.namespace("Toc.administrators");';
?>

Ext.override(Toc.desktop.AdministratorsWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('administrators-win');
     
    if (!win) {
      var grd = Ext.create('Toc.administrators.AdministratorsGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateAdministrators(grd);}, this);
      grd.on('edit', function(record) {this.onEditAdministrators(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'administrators-win',
        title: '<?php echo lang('heading_administrators_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-administrators-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateAdministrators: function(grd) {
    var dlg = this.createAdministratorsDialog({});
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditAdministrators: function(grd, record) {
    var dlg = this.createAdministratorsDialog({'aID': record.get('id')});
    dlg.setTitle(record.get('user_name'));
    
    dlg.pnlAccessTree.getStore().on('load', function() {dlg.pnlAccessTree.expandAll();}, this);
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('id'));
  },
  
  createAdministratorsDialog: function(config) {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('administrators-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow(config, Toc.administrators.AdministratorsDialog);
      
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
/* Location: ./system/modules/administrators/views/main.php */
