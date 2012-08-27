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
 * @filesource .system/modules/information/views/main.php
 */

  echo 'Ext.namespace("Toc.information");';
?>

Ext.override(Toc.desktop.InformationWindow, {

  createWindow : function() {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('information-win');
     
    if (!win) {
      var grd = Ext.create('Toc.information.InformationGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateInformation(grd);}, this);
      grd.on('edit', function(record) {this.onEditInformation(grd, record);}, this);

      win = desktop.createWindow({
        id: 'information-win',
        title: '<?= lang('heading_information_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-information-win',
        layout: 'fit',
        items: grd
      });
    }
           
    win.show();
  },
  
  onCreateInformation: function(grd) {
    var dlg = this.createInformationDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditInformation: function(grd, record) {
    var dlg = this.createInformationDialog();
    dlg.setTitle(record.get("articles_name"));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('articles_id'));
  },
  
  createInformationDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('information-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.information.InformationDialog);
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
/* Location: ./system/modules/information/views/main.php */
