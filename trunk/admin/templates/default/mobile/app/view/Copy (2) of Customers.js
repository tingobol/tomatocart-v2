/**
 * Demonstrates a range of Button options the framework offers out of the box
 */
Ext.require([
    'Ext.ux.touch.grid.View',
    'Ext.ux.touch.grid.feature.Feature',
    'Ext.ux.touch.grid.feature.Paging',
    'Ext.ux.touch.grid.feature.Sorter'
]);

Ext.define('CustomersModel', {
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
   
    
    proxy: {
        type: 'ajax',
        url    : base_url,
        extraParams: {
            module: 'customers',
            action: 'list_customers'
        },
        reader: {
            type: 'json',
            rootPorperty: 'records'
        }
    } 
},
});

//var store = Ext.create('Ext.data.Store', {
//    model: 'CustomersModel',
//    autoLoad: true
//});
store = Ext.create('Ext.data.Store', {
    fields:[
      'customers_id',
      'customers_lastname',
      'customers_firstname',
      'customers_credits',
      'date_account_created', //{name: 'date_account_created', type: 'date', dateFormat: 'Y-m-d H:i:s'},
      'customers_status',
      'customers_info'
    ],
    pageSize: 3,
    proxy: {
      type: 'ajax',
      url : base_url,
      extraParams: {
        module: 'customers',
        action: 'list_customers'
      },
      reader: {
        type: 'json',
        rootPorperty: 'records'
      }
    },
    autoLoad: true
});

Ext.define('Kitchensink.view.Customers', {
    extend: 'Ext.ux.touch.grid.View',
    store: store,
    constructor: function(config) {
      config = config || {};
    
      config.features = [
         {
             ftype    : 'Ext.ux.touch.grid.feature.Paging',
             launchFn : 'initialize'
         }
      ];
      
      config.columns = [
        {
            header    : 'id',
            dataIndex : 'customers_id',
            style     : 'padding-left: 1em;',
            width     : '30%'
        },
        {
            header    : 'First Name',
            dataIndex : 'customers_firstname',
            style     : 'padding-left: 1em;',
            width     : '30%'
        },
        {
            header    : 'Last Name',
            dataIndex : 'customers_lastname',
            style     : 'padding-left: 1em;',
            width     : '40%'
        }
    ];
      
      config.store = store;
      
      this.callParent([config]);
    }
});
