/**
 * Demonstrates a range of Button options the framework offers out of the box
 */
Ext.require([
    'Ext.ux.touch.grid.View',
    'Ext.ux.touch.grid.feature.Feature',
    'Ext.ux.touch.grid.feature.Paging',
    'Ext.ux.touch.grid.feature.Sorter'
]);

Ext.define('TestModel', {
    extend : 'Ext.data.Model',

    config : {
        fields : [
              'customers_id',
              'customers_lastname',
              'customers_firstname',
              'customers_credits',
              {name: 'date_account_created', type: 'date', dateFormat: 'Y-m-d H:i:s'},
              'customers_status',
              'customers_info'
        ],

        proxy : {
            type   : 'ajax',
            url    : base_url, 
            extraParams: {
                module: 'customers',
                action: 'list_customers'
            },
            reader : {
                type         : 'json',
                rootProperty : 'records'
            }
        }
    }
});

var store = Ext.create('Ext.data.Store', {
    model    : 'TestModel',
    pageSize: 3,
    autoLoad : true
});


Ext.define('Kitchensink.view.Customers', {
    extend: 'Ext.ux.touch.grid.View',
    store      : store,
    constructor: function(config) {
      config = config || {};
    
      config.features = [
         {ftype    : 'Ext.ux.touch.grid.feature.Paging',
          launchFn : 'initialize'}];
      
      config.columns = [
        {
            header    : 'First Name',
            dataIndex : 'customers_firstname',
            style     : 'padding-left: 1em;',
            width     : '20%'
        },
        {
            header    : 'Last Name',
            dataIndex : 'customers_lastname',
            style     : 'padding-left: 1em;',
            width     : '20%'
        },
        {
            header    : 'Credits',
            dataIndex : 'customers_credits',
            style     : 'padding-left: 1em;',
            width     : '20%'
        },
        {
            header    : 'Date Created',
            dataIndex : 'date_account_created',
            style     : 'padding-left: 1em;',
            width     : '20%',
            renderer: Ext.util.Format.date
        },
        {
            header    : 'Status',
            dataIndex : 'customers_status',
            style     : 'padding-left: 1em;',
            width     : '20%'
        }
    ];
      
      config.store = store;
      
      this.callParent([config]);
    }
});
