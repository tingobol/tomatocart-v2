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
 * @filesource .system/modules/zone_groups/views/main.php
 */

  echo 'Ext.namespace("Toc.zone_groups");';
?>

Ext.override(Toc.desktop.ZoneGroupsWindow, {
  createWindow: function () {
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('zone_groups-win');
    
    if (!win) {
      pnl = Ext.create('Toc.zone_groups.MainPanel');
      
      pnl.grdZoneGroups.on('notifysuccess', this.onShowNotification, this);
      pnl.grdZoneGroups.on('create', function() {this.onCreateZoneGroups(pnl.grdZoneGroups);}, this);
      pnl.grdZoneGroups.on('edit', function(record) {this.onEditZoneGroups(pnl.grdZoneGroups, record);}, this);
      
      pnl.grdZoneEntries.on('notifysuccess', this.onShowNotification, this);
      pnl.grdZoneEntries.on('create', function(geoZoneId) {this.onCreateZoneEntries(pnl.grdZoneEntries, geoZoneId);}, this);
      pnl.grdZoneEntries.on('edit', function(params) {this.onEditZoneEntries(pnl.grdZoneEntries, params);}, this);
      
      win = desktop.createWindow({
        id: 'zone_groups-win',
        title: '<?= lang("heading_zone_groups_title"); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-zone_groups-win',
        layout: 'fit',
        items: pnl
      });
    }
    
    win.show();
  },
  
  onCreateZoneGroups: function(grdZoneGroups) {
    var dlg = this.createZoneGroupsDialog();
    
    this.onSaveSuccess(dlg, grdZoneGroups);
    
    dlg.show();
  },
  
  onEditZoneGroups: function(grdZoneGroups, record) {
    var dlg = this.createZoneGroupsDialog();
    dlg.setTitle(record.get('geo_zone_name'));
    
    this.onSaveSuccess(dlg, grdZoneGroups);
    
    dlg.show(record.get('geo_zone_id'));
  },
  
  onCreateZoneEntries: function(grdZoneEntries, geoZoneId) {
    if (geoZoneId > 0) {
      var dlg = this.createZoneEntriesDialog();
      
      this.onSaveSuccess(dlg, grdZoneEntries);
      
      dlg.show(geoZoneId);
    }else {
      Ext.MessageBox.alert(TocLanguage.msgInfoTitle, TocLanguage.msgMustSelectOne);
    }
  },
  
  onEditZoneEntries: function(grdZoneEntries, params) {
    var dlg = this.createZoneEntriesDialog();
    dlg.setTitle(params.geoZoneName);
    
    var geoZoneEntryId = params.record.get('geo_zone_entry_id');
    
    this.onSaveSuccess(dlg, grdZoneEntries);
    
    dlg.show(params.geoZoneId, geoZoneEntryId);
  },
  
  createZoneGroupsDialog: function () {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('zone_groups-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.zone_groups.ZoneGroupsDialog);
    }
    
    return dlg;
  },
  
  createZoneEntriesDialog: function () {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('zone_entries-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.zone_groups.ZoneEntriesDialog);
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