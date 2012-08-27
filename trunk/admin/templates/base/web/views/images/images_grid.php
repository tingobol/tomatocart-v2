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
 * @filesource ./system/modules/images/views/images_grid.php
 */
?>

Ext.define('Toc.images.ImagesGrid', {
  extend: 'Ext.grid.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.border = false;
    config.region = 'center';
    
    config.store = Ext.create('Ext.data.Store', {
      fields:[
        'module', 
        'run'
      ],
      pageSize: Toc.CONF.GRID_PAGE_SIZE,
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'images',
          action: 'list_images'
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
      {header: '<?= lang('table_heading_modules'); ?>', dataIndex: 'module', flex: 1},
      {
        xtype: 'actioncolumn', 
        width: 50,
        header: '<?= lang("table_heading_action"); ?>',
        items: [{
          tooltip: TocLanguage.tipExecute,
          iconCls: 'icon-action icon-execute-record',
          handler: function(grid, rowIndex, colIndex) {
            var rec = grid.getStore().getAt(rowIndex);
            
            this.fireEvent(rec.get('run'), rec);
          },
          scope: this
        }]
      }
    ];
    
    config.tbar = [
      {
        text: TocLanguage.btnRefresh,
        iconCls: 'refresh',
        handler: this.onRefresh,
        scope: this
      }
    ];
    
    this.addEvents({'checkimages': true, 'resizeimages': true});
    
    this.callParent([config]);
  },
  
  onRefresh: function() {
    this.getStore().load();
  }
});

/* End of file images_grid.php */
/* Location: ./system/modules/images/views/images_grid.php */
