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
 * @filesource address_book_grid.php
 */
?>

Ext.define('Toc.customers.AddressBookDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'address-book-dialog-win';
    config.title = '<?= lang('action_heading_new_address_book_entry'); ?>';
    config.modal = true;
    config.width = 500;
    config.iconCls = 'icon-customers-win';
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess' : true});
    
    this.callParent([config]);  
  },
  
  show: function(customersId, abId) {
    var addressBookId = abId || null;
    
    this.frmAddressBook.form.reset();
    this.frmAddressBook.form.baseParams['customers_id'] = customersId;
    this.frmAddressBook.form.baseParams['address_book_id'] = addressBookId;
    
    if (addressBookId > 0) {
      this.frmAddressBook.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'customers',
          action: 'load_address_book',
          address_book_id: addressBookId
        },
        success: function(form, action) {
          if (action.result.data.primary == true) {
            Ext.getCmp('primary').disable();
          }
          
          this.cboCountries.setRawValue(action.result.data.country_title);

          this.cboZones.enable();
          
          this.dsZone.proxy.extraParams['country_id'] = action.result.data.country_id;
          
          var onDsZonesLoad = function() {
            this.cboZones.setValue(action.result.data.zone_code);
            this.dsZone.removeListener('load', onDsZonesLoad, this);
          };
          this.dsZone.on('load', onDsZonesLoad, this);
          this.dsZone.load();
          
          Toc.customers.AddressBookDialog.superclass.show.call(this);
        },
        failure: function() {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {   
      Toc.customers.AddressBookDialog.superclass.show.call(this);
    } 
  },
  
  buildForm: function() {
    var dsCountries = Ext.create('Ext.data.Store', {
      fields:[
        'country_id',
        'country_title'
      ],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'customers',
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
      fieldLabel: '<?= lang('field_country'); ?>',
      store: dsCountries, 
      name: 'country_id', 
      queryMode: 'local',
      displayField: 'country_title', 
      valueField: 'country_id', 
      editable: false,
      forceSelection: true,      
      emptyText: '<?= lang('none'); ?>',
      listeners :{
        select: this.onCboCountriesSelect,
        scope: this
      } 
    });
    
    this.dsZone = Ext.create('Ext.data.Store', {
      fields:[
        'zone_code',
        'zone_name'
      ],
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'customers',
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
      fieldLabel: '<?= lang('field_state'); ?>',
      disabled: true, 
      store: this.dsZone, 
      name: 'z_code', 
      queryMode: 'local',
      displayField: 'zone_name', 
      valueField: 'zone_code', 
      editable: false,
      forceSelection: true,      
      emptyText: '<?= lang('none'); ?>'
    });
    
    this.frmAddressBook = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        'module' : 'customers',
        'action' : 'save_address_book'
      },
      fieldDefaults: {
        labelAlign: 'left',
        labelWidth: 150,
        anchor: '98%'
      },
      bodyPadding: 10,
      border: false,
      items: [
        {
          layout: 'column',
          border: false,
          items: [
            { 
              width: 220,
              border: false,
              items:[
                {fieldLabel: '<?= lang('field_gender'); ?>', boxLabel: '<?= lang('gender_male'); ?>' , name: 'gender', xtype:'radio', inputValue: 'm'}
              ]
            },
            { 
              width: 120,
              border: false,
              items:[
                { hideLabel: true, boxLabel: '<?= lang('gender_female'); ?>' , name: 'gender', xtype:'radio', inputValue: 'f', checked: true}
              ]
            }
          ]  
        },
        {xtype: 'textfield', fieldLabel: '<?= lang('field_first_name'); ?>', name: 'firstname'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_last_name'); ?>', name: 'lastname'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_company'); ?>', name: 'company'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_street_address'); ?>', name: 'street_address'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_suburb'); ?>', name: 'suburb'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_post_code'); ?>', name: 'postcode'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_city'); ?>', name: 'city'},
        this.cboCountries,
        this.cboZones,
        {xtype: 'textfield', fieldLabel: '<?= lang('field_telephone_number'); ?>', name: 'telephone_number'},
        {xtype: 'textfield', fieldLabel: '<?= lang('field_fax_number'); ?>', name: 'fax_number'},
        {xtype: 'checkbox',  fieldLabel: '<?= lang('field_set_as_primary'); ?>', name: 'primary', id: 'primary', anchor: ''} 
      ]
    });
    
    return this.frmAddressBook;
  },
  
  onCboCountriesSelect: function(combo, value) {
    this.cboZones.enable();
    this.cboZones.reset();
    this.dsZone.proxy.extraParams['country_id'] = value[0].get('country_id');
    this.dsZone.load();
  },
  
  submitForm : function() {
    this.frmAddressBook.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success:function(form, action) {
        this.fireEvent('savesuccess', action.result.feedback); 
        this.close();
      },
      failure: function(form, action) {
        if (action.failureType != 'client') {         
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);      
        }         
      },
      scope: this
    });   
  }
});


/* End of file address_book_grid.php */
/* Location: ./system/modules/customers/views/address_book_grid.php */