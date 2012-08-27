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
        url    : base_url + 'http://www.toc2.me/admin/index.php/index?_dc=1343907791471&module=customers&action=list_customers&page=1&start=0&limit=3',
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

var store = Ext.create('Ext.data.Store', {
    model: 'CustomersModel',
    autoLoad: true
});


Ext.define('Kitchensink.view.Customers', {
    extend: 'Ext.ux.touch.grid.View',
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
            width     : '30%'
        },
        {
            header    : 'Last Name',
            dataIndex : 'customers_lastname',
            style     : 'padding-left: 1em;',
            width     : '70%'
        }
    ];
      
      config.store = store;
      
      this.callParent([config]);
    }
});
