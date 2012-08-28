/**
 * Demonstrates a 'slide' card transition, which shows a new item by sliding the
 * new item in and the old item out simultaneously, in this case with the new
 * item coming in from the right
 */
defaultStore = 'store1';
window.generateData = function(n, floor) {
    var data = [], i;

    floor = (!floor && floor !== 0) ? 20 : floor;

    for (i = 0; i < (n || 12); i++) {
        data.push({
            product : 'Product' + i,
            data1 : Math.floor(Math.max((Math.random() * 100), floor)),
            data2 : Math.floor(Math.max((Math.random() * 100), floor)),
            data3 : Math.floor(Math.max((Math.random() * 100), floor)),
            2003 : Math.floor(Math.max((Math.random() * 100), floor)),
            2004 : Math.floor(Math.max((Math.random() * 100), floor)),
            2005 : Math.floor(Math.max((Math.random() * 100), floor)),
            2006 : Math.floor(Math.max((Math.random() * 100), floor)),
            2007 : Math.floor(Math.max((Math.random() * 100), floor)),
            2008 : Math.floor(Math.max((Math.random() * 100), floor)),
            2009 : Math.floor(Math.max((Math.random() * 100), floor)),
            2010 : Math.floor(Math.max((Math.random() * 100), floor)),
            iphone : Math.floor(Math.max((Math.random() * 100), floor)),
            android : Math.floor(Math.max((Math.random() * 100), floor)),
            ipad : Math.floor(Math.max((Math.random() * 100), floor))
        });
    }
    return data;
};

window.store1 = new Ext.create('Ext.data.JsonStore', {
    fields : [ 'product', 'data1', 'data2', 'data3', '2003', '2004', '2005', '2006', '2007', '2008', '2009', '2010',
            'iphone', 'android', 'ipad' ],
    data : generateData(5, 20)
});

window.createPanel = function(chart) {
    return window.panel = Ext.create('Ext.chart.Panel', {
        title : 'test',
        height: 600,
        width: 1000,
        buttons : [ {
            xtype : 'button',
            iconCls : 'help',
            iconMask : true,
            ui : 'plain'
        }, {
            xtype : 'button',
            iconCls : 'shuffle',
            iconMask : true,
            ui : 'plain'
        } ],
        chart : chart
    });
}

var chart = Ext.create('Ext.chart.Chart', {
    layout : 'fit',
    themeCls : 'bar1',
    theme : 'Demo',
    store : window.store1,
    animate : true,
    shadow : true,
    legend : {
        position : {
            portrait : 'bottom',
            landscape : 'right'
        },
        labelFont : '24px Arial'
    },
    interactions : [
            {
                type : 'reset'
            },
            {
                type : 'togglestacked'
            },
            {
                type : 'panzoom',
                axes : {
                    left : {}
                }
            },
            'itemhighlight',
            {
                type : 'iteminfo',
                gesture : 'longpress',
                panel : {
                    items : [ {
                        docked : 'top',
                        xtype : 'toolbar',
                        title : 'Details'
                    } ]
                },
                listeners : {
                    'show' : function(me, item, panel) {
                        panel.setHtml('<ul><li><b>Month:</b> ' + item.value[0] + '</li><li><b>Value: </b> '
                                + item.value[1] + '</li></ul>');
                    }
                }
            },
            {
                type : 'itemcompare',
                offset : {
                    x : -10
                },
                listeners : {
                    'show' : function(interaction) {
                        var val1 = interaction.item1.value, val2 = interaction.item2.value;

                        chartPanel.descriptionPanel.setTitle(val1[0] + ' to ' + val2[0] + ' : '
                                + Math.round((val2[1] - val1[1]) / val1[1] * 100) + '%');
                        chartPanel.headerPanel.getLayout().setAnimation('slide');
                        chartPanel.headerPanel.setActiveItem(1);
                    },
                    'hide' : function() {
                        var animation = chartPanel.headerPanel.getLayout().getAnimation();
                        if (animation) {
                            animation.setReverse(true);
                        }
                        chartPanel.headerPanel.setActiveItem(0);
                    }
                }
            } ],
    axes : [ 
        {
            type : 'Numeric',
            position : 'bottom',
            fields : [ '2008', '2009', '2010' ],
            label : {
                renderer : function(v) {
                    return v.toFixed(0);
            }
        },
        title : 'Number of Hits',
        minimum : 0
    }, {
        type : 'Category',
        position : 'left',
        fields : [ 'product' ],
        title : 'Month of the Year'
    } ],
    series : [ {
        type : 'bar',
        xField : 'product',
        yField : [ 'Quantity', 'Total', 'Average Price' ],
        axis : 'bottom',
        highlight : true,
        showInLegend : true
    } ]
});

var chartPanel = Ext.create('Ext.chart.Panel', {
    config: {
        items: [
            {
                xtype: 'toolbar',
                docked: 'top',
                defaults: {
                    iconMask: true
                },
                items: [
                    { xtype: 'spacer' },
                    { iconCls: 'action' },
                    { iconCls: 'add' },
                    { iconCls: 'arrow_down' },
                    { iconCls: 'arrow_left' },
                    { iconCls: 'arrow_up' },
                    { iconCls: 'compose' },
                    { iconCls: 'delete' },
                    { iconCls: 'organize' },
                    { iconCls: 'refresh' },
                    { xtype: 'spacer' }
                ]
            }
        ]
    },
    buttons : [{
        xtype: 'button',
        iconCls: 'shuffle',
        iconMask: true,
        ui: 'plain'
    }],
    chart : chart
});

Ext.define('Kitchensink.view.ProductsPurchased', {
    extend : 'Ext.Panel',
    layout: 'fit',
    requires : [ 'Ext.chart.Panel', 'Ext.chart.axis.Numeric', 'Ext.chart.axis.Category', 'Ext.chart.series.Area' ],
    config : {
        items : chartPanel
    }
});
