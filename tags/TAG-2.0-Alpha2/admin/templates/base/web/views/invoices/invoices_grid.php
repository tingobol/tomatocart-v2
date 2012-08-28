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
 * @filesource system/modules/invoices/views/invoices_grid.php
 */
?>

Ext.define('Toc.invoices.InvoicesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.viewConfig = {emptyText: TocLanguage.gridNoRecords};
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'orders_id', 
        'customers_name',
        {name: 'order_total', type: 'string'},
        'date_purchased',
        'orders_status_name',
        'invoices_number',
        'invoices_date',
        'shipping_address',
        'shipping_method',
        'billing_address',
        'payment_method',
        'products',
        'totals'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'invoices',
          action: 'list_invoices'
        },
        reader: {
          type: 'json',
          root: Toc.CONF.JSON_READER_ROOT,
          totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
        }
      },
      autoLoad: true
    });
    
    config.columns =[
      { header: '<?= lang('table_heading_invoices_number'); ?>', dataIndex: 'invoices_number', align: 'center', sortable: true, width: 100},
      {header: 'OID', dataIndex: 'orders_id', width: 30, align: 'center'},
      { header: '<?= lang('table_heading_customers'); ?>', dataIndex: 'customers_name', flex: 1},
      { header: '<?= lang('table_heading_order_total'); ?>', dataIndex: 'order_total', align: 'right', width: 100, sortable: true},
      { header: '<?= lang('table_heading_date_purchased'); ?>', dataIndex: 'date_purchased', align: 'center', width: 110, sortable: true},
      { header: '<?= lang('table_heading_status'); ?>', dataIndex: 'orders_status_name', align: 'center', width: 90, sortable: true},
      { header: '<?= lang('table_heading_invoices_date'); ?>', dataIndex: 'invoices_date', align: 'center', width: 110, sortable: true},
      {
        xtype:'actioncolumn', 
        width: 80,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          iconCls: 'icon-action icon-view-record',
          tooltip: '<?= lang('tip_view_invoice');?>',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent('view', rec);
          },
          scope: this
        },{
          iconCls: 'icon-action icon-invoice-pdf-record',
          tooltip: '<?= lang('tip_print_invoice');?>',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.onInvoice(rec);
          },
          scope: this
        }]
      }
    ];
    
    config.plugins = [
      {
        ptype: 'rowexpander',
        rowBodyTpl : [
        
          '<div class="order_details">',
            '<table width="98%">',
             '<tr>',
               '<td width="25%">',
                 '<strong><?= lang('subsection_shipping_address'); ?></strong>',
                 '<p>{shipping_address}</p>',
                 '<strong><?= lang('subsection_delivery_method'); ?></strong>',
                 '<p>{shipping_method}</p>',
               '</td>',
               '<td width="25%">',
                 '<strong><?= lang('subsection_billing_address'); ?></strong>',
                 '<p>{billing_address}</p>',
                 '<strong><?= lang('subsection_payment_method'); ?></strong>',
                 '<p>{payment_method}</p>',
               '</td>',
               '<td>',
                 '<strong><?= lang('subsection_products'); ?></strong>',
                 '<div class="order_products">{products}</div>',
                 '<div class="order_totals">{totals}</div>',
               '</td>',
             '</tr>',
           '</table>',
         '</div>'
        ]
      }
    ];
    
    this.txtOrderId = Ext.create('Ext.form.TextField', {
      width: 120,
      emptyText: '<?= lang('operation_heading_order_id'); ?>'
    });
    
    this.txtCustomerId = Ext.create('Ext.form.TextField', {
      width: 120,
      emptyText: '<?= lang('operation_heading_customer_id'); ?>'
    });
    
    config.tbar = [
      { 
        text: TocLanguage.btnRefresh,
        iconCls:'refresh',
        handler: this.onRefresh,
        scope: this
      },
      '->',
      this.txtOrderId,
      ' ',
      this.txtCustomerId,
      {
        name: 'search',
        handler: this.onSearch,
        iconCls: 'search',
        scope: this
      } 
    ];
    
    config.dockedItems = [{
      xtype: 'pagingtoolbar',
      store: config.store,
      dock: 'bottom',
      displayInfo: true
    }];
    
    this.addEvents({'view': true});
    
    this.callParent([config]);
  },
  
  onInvoice: function(record){
    this.openWin('<?= site_url('index/ajax'); ?>' + '?module=invoices&action=create_invoice&orders_id=' + record.get('orders_id'), 900, 500);
  },
  
  onSearch: function() {
    var proxy = this.getStore().getProxy();
    
    proxy.extraParams['orders_id'] = this.txtOrderId.getValue() || null;
    proxy.extraParams['customers_id'] = this.txtCustomerId.getValue() || null;
    
    this.onRefresh();
  },
  
  onRefresh: function() {
    this.getStore().load();
  },
  
  openWin: function(u, w, h) {
    var l = (screen.width - w) / 2;
    var t = (screen.height - h) / 2;
    var s = 'width=' + w + ', height=' + h + ', top=' + t + ', left=' + l;
    s += ', toolbar=no, scrollbars=no, menubar=yes, location=no, resizable=no';
    window.open(u, '', s);
  }
});

/* End of file invoices_grid.php */
/* Location: system/modules/invoices/views/invoices_grid.php */
