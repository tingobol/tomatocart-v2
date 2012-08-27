defaultStore = 'store1';
window.generateData = function(n, floor) {
    var data = [];
    
    data.push({
        'products_id' : 1,
        'products_name' : 'iPhone 4s',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 2,
        'products_name' : 'iPhone 4',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 3,
        'products_name' : 'iPad3 (16G)',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 4,
        'products_name' : 'iPad3 (32G)',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 5,
        'products_name' : 'iTouch',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 6,
        'products_name' : 'iTouch 32G',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    
    data.push({
        'products_id' : 7,
        'products_name' : 'Macbook',
        'quantity' : Math.floor(Math.max((Math.random() * 100), floor)),
        'total' : Math.floor(Math.max((Math.random() * 100), floor)),
        'average_price' : Math.floor(Math.max((Math.random() * 100), floor))
    });
    return data;
};

store = new Ext.create('Ext.data.JsonStore', {
    fields : [ 'products_name', 'quantity', 'total', 'average_price'],
    pageSize: 3,
    proxy: {
        type: 'ajax',
        url : base_url,
        extraParams: {
          module: 'reports_products',
          action: 'list_products_purchased'
        },
        reader: {
          type: 'json',
          rootProperty: 'records'
        }
      },
      autoLoad: true
});

createPanel = function(chart) {
    return window.panel = Ext.create('Ext.chart.Panel', {
        layout: {
          type: 'vbox'
        },
        chart : chart
    });
}

var chart = Ext.create('Ext.chart.Chart', {
    themeCls : 'bar1',
    theme : 'Demo',
    viewBox: true,
    height: 550,
    store : store,
    animate : true,
    shadow : true,
    scrollable: true,
    legend : {
        position : {
            portrait : 'bottom',
            landscape : 'right'
        },
        labelFont : '24px Arial'
    },
    axes : [ 
        {
            type : 'Numeric',

            position : 'bottom',
            fields : [ 'quantity'],
            label : {
                renderer : function(v) {
                    return v.toFixed(0);
                },
                rotate: {
                    degrees: 315
                }
            },
        title : 'quantity',
        minimum : 0
    }, {
        type : 'Category',
        position : 'left',
        fields : [ 'products_name' ]
    } ],
    series : [ {
        type : 'bar',
        xField : 'products_name',
        yField : [ 'quantity'],
        axis : 'true',
        highlight : true,
        tips: {
            trackMouse: true,
            width: 140,
            height: 28,
            renderer: function(storeItem, item) {
              this.setTitle(storeItem.get('quantity') + ': ' + storeItem.get('total') + ' views');
            }
          },
          label: {
            display: 'insideEnd',
              field: 'total',
              orientation: 'horizontal',
              color: '#333',
              'text-anchor': 'middle'
          },

        showInLegend : false
    } ]
});

var startDate = new Date();
startDate.setDate(startDate.getDate() - 6);

Ext.define('Kitchensink.view.ProductsPurchased', {
    extend : 'Ext.Panel',
    layout: 'fit',
    requires : [ 'Ext.chart.Panel', 'Ext.chart.axis.Numeric', 'Ext.chart.axis.Category', 'Ext.chart.series.Area' ],
    config : {
        items : [
            createPanel(chart),
            {
                xtype: 'toolbar',
                docked: 'top',
                ui: 'light',
                defaults: {
                    iconMask: true
                },
                items: [
                    {xtype: 'spacer'},
                    {xtype: 'datepickerfield', label: 'Start: ', value: startDate},
                    {xtype: 'datepickerfield', label: 'End: ', value: new Date()},
                    {iconCls: 'search'}
                ]
            }
        ]
    }
});
