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
 * @filesource ./system/modules/specials/views/main.php
 */

  echo 'Ext.namespace("Toc.specials");';
?>

Ext.override(Toc.desktop.SpecialsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('specials-win');
     
    if(!win){
      var grd = Ext.create('Toc.specials.SpecialsGrid');
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateSpecial(grd);}, this);
      grd.on('edit', function(record) {this.onEditSpecial(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'specials-win',
        title: '<?php echo lang('heading_specials_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-specials-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateSpecial: function(grd) {
    var dlg = this.createSpecialsDialog();
    dlg.setTitle('<?php echo lang("action_heading_new_special"); ?>');
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditSpecial: function(grd, record) {
    var specialsId = record.get('specials_id');
    var dlg = this.createSpecialsDialog();
    dlg.setTitle(record.get('products_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(specialsId);
  },
    
  createSpecialsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('specials-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.specials.SpecialsDialog);
    }
    
    return dlg;
  },
  
  createBatchSpecialsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('batch-specials-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.specials.BatchSpecialsDialog);
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
/* Location: ./system/modules/specials/views/main.php */

