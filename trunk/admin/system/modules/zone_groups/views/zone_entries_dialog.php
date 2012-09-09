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
 * @filesource ./system/modules/zone_groups/views/zone_entries_dialog.php
 */
?>

Ext.define('Toc.zone_groups.ZoneEntriesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'zone_entries-dialog-win';
    config.title = '<?php echo lang("action_heading_new_zone_group"); ?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-zone_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);  
  },
  
  show: function (geoZoneId, entryId) {
    this.geoZoneId = geoZoneId || null;
    var geoZoneEntryId = entryId || null;  
        
    this.frmZoneEntry.form.baseParams['geo_zone_id'] = this.geoZoneId;
    this.frmZoneEntry.form.baseParams['geo_zone_entry_id'] = geoZoneEntryId;

    if (geoZoneEntryId > 0) {
      this.frmZoneEntry.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'zone_groups',
          action: 'load_zone_entry'
        },
        success: function (form, action) {
          this.cboCountries.setValue(action.result.data.zone_country_id);
          this.cboCountries.setRawValue(action.result.data.countries_name);
          this.updateCboZones(action.result.data.zone_id);
          
          Toc.zone_groups.ZoneEntriesDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }  
  },
  
  buildForm: function() {
    var dsCountries = Ext.create('Ext.data.Store', {
      fields: ['countries_id', 'countries_name'],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'zone_groups',
          action: 'get_countries'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    this.cboCountries = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_country'); ?>',
      store: dsCountries, 
      name: 'countries_id', 
      queryMode: 'local',
      displayField: 'countries_name', 
      valueField: 'countries_id', 
      editable: false,
      forceSelection: true,
      listeners :{
        select: this.onCboCountriesSelect,
        scope: this
      } 
    });
    
    this.dsZones = Ext.create('Ext.data.Store', {
      fields:[
        'zone_id',
        'zone_name'
      ],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'zone_groups',
          action: 'get_zones'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      }
    });
    
    this.cboZones = Ext.create('Ext.form.ComboBox', {
      fieldLabel: '<?php echo lang('field_zone'); ?>',
      disabled: true, 
      store: this.dsZones, 
      name: 'zone_id', 
      queryMode: 'local',
      displayField: 'zone_name', 
      valueField: 'zone_id', 
      editable: false,
      forceSelection: true
    });
    
    this.frmZoneEntry = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'zone_groups',
        action: 'save_zone_entry'
      },
      border: false,
      bodyPadding: 5,
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      items: [this.cboCountries, this.cboZones]
    });
    
    return this.frmZoneEntry;
  },
  
  onCboCountriesSelect: function() {
    this.updateCboZones();
  },
  
  updateCboZones: function(zoneId) {
    this.cboZones.enable();  
    this.dsZones.getProxy().extraParams['countries_id'] = this.cboCountries.getValue();  
    
    if(zoneId) {
      this.dsZones.on('load', function(){
        this.cboZones.setValue(zoneId);
      }, this);
    }
    
    this.dsZones.load();
  },
  
  submitForm: function() {
    this.frmZoneEntry.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success:function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client'){
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});

/* End of file zone_entries_dialog.php */
/* Location: ./system/modules/zone_groups/views/zone_entries_dialog.php */
