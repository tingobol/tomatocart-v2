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
 * @filesource ./system/modules/images/views/images_check_dialog.php
 */
?>

Ext.define('Toc.images.ImagesCheckDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'images-check-dialog-win';
    config.layout = 'fit';
    config.width = 480;
    config.height = 300;
    config.modal = true;
    config.iconCls = 'icon-images-win';
    
    config.items = this.buildGrid();
    
    config.buttons = [
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        },
        scope: this
      }
    ];
    
    this.callParent([config]);
  },
  
  buildGrid: function() {
    this.grdImages = Ext.create('Ext.grid.Panel', {
      store: Ext.create('Ext.data.Store', {
        fields:[
          'group', 
          'count'
        ],
        pageSize: Toc.CONF.GRID_PAGE_SIZE,
        proxy: {
          type: 'ajax',
          url : Toc.CONF.CONN_URL,
          extraParams: {
            module: 'images',
            action: 'check_images'
          },
          reader: {
            type: 'json',
            root: Toc.CONF.JSON_READER_ROOT,
            totalProperty: Toc.CONF.JSON_READER_TOTAL_PROPERTY
          }
        },
        autoLoad: true
      }),
      
      border: false,
      
      columns: [
        {header: '<?php echo lang('images_check_table_heading_groups'); ?>', dataIndex: 'group', flex: 1},
        {header: '<?php echo lang('images_check_table_heading_results'); ?>', dataIndex: 'count', align: 'center', width: 200},
      ],
      
      tbar: [
        {
          text: TocLanguage.btnRefresh,
          iconCls: 'refresh',
          handler: this.onRefresh,
          scope: this
        }
      ]
    });
    
    return this.grdImages;
  },
  
  onRefresh: function () {
    this.grdImages.getStore().load();
  }
});

/* End of file images_check_dialog.php */
/* Location: ./system/modules/images/views/images_check_dialog.php */
