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
 * @filesource ./system/modules/zone_groups/views/zone_groups_dialog.php
 */
?>

Ext.define('Toc.zone_groups.ZoneGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'zone_groups-dialog-win';
    config.title = '<?= lang("action_heading_new_zone_group"); ?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-zone_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
        },
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var geoZoneId = id || null;
      
    this.frmZoneGroup.form.baseParams['geo_zone_id'] = geoZoneId;
    
    if (geoZoneId > 0) {
      this.frmZoneGroup.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'zone_groups',
          action: 'load_zone_group'
        },              
        success: function (form, action) {
          Toc.zone_groups.ZoneGroupsDialog.superclass.show.call(this);
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
    this.frmZoneGroup = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'zone_groups',
        action: 'save_zone_group'
      },
      fieldDefaults: {
        labelSeparator: '',
        anchor: '97%'
      },
      border: false,
      bodyPadding: 5,
      items: [
        {
          xtype: 'textfield',
          fieldLabel: '<?= lang("field_name"); ?>',
          name: 'geo_zone_name',
          allowBlank: false
        },
        {
          xtype: 'textfield',
          fieldLabel: '<?= lang("field_description"); ?>',
          name: 'geo_zone_description'
        }
      ]
    });
    
    return this.frmZoneGroup;
  },
  
  submitForm: function () {
    this.frmZoneGroup.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });
  }
});


/* End of file zone_groups_dialog.php */
/* Location: ./system/modules/zone_groups/views/zone_groups_dialog.php */
