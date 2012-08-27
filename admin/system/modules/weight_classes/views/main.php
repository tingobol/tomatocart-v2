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
 * @filesource modules/weight_classes/views/main.php
 */

  echo 'Ext.namespace("Toc.weight_classes");';
  
?>

Ext.override(Toc.desktop.WeightClassesWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('weight_classes-win');
     
    if(!win){
      var grd = Ext.create('Toc.weight_classes.WeightClassesGrid');
      
      grd.on('notifysuccess', this.onShowNotification, this);
      grd.on('create', function() {this.onCreateWeightClasses(grd);}, this);
      grd.on('edit', function(record) {this.onEditWeightClasses(grd, record);}, this);
      
      win = desktop.createWindow({
        id: 'weight_classes-win',
        title: '<?= lang('heading_weight_classes_title'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-weight_classes-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onCreateWeightClasses: function(grd) {
    var dlg = this.createWeightClassesDialog();
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show();
  },
  
  onEditWeightClasses: function(grd, record) {
    var dlg = this.createWeightClassesDialog();
    dlg.setTitle(record.get('action_heading_new_weight_class'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('weight_class_id'));
  },
    
  createWeightClassesDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('weight_classes-dialog-win');
    
    if(!dlg){
      dlg = desktop.createWindow({}, Toc.weight_classes.WeightClassesDialog);
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
/* Location: ./system/modules/weight_classes/views/main.php */
