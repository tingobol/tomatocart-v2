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
 * @filesource ./system/modules/server_info/views/server_info_dialog.php
 */
?>

Ext.define('Toc.server_info.ServerInfoDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'server_info-win';
    config.title = '<?php echo lang('heading_server_info_title'); ?>';
    config.width = 800;
    config.height = 400;
    config.iconCls = 'icon-server_info-win';
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var frmServerInfo = Ext.create('Ext.form.Panel', {
      border: false,
      layout: 'anchor',
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '98%',
        labelSeparator: '',
        labelWidth: 150
      },
      items: [
        {
          border: false,
          html: '<p class="form-info"><b><?php echo $project_version; ?></b></p>'
        },
        {
          layout: 'column',
          border: false,
          items: [
            {
              layout: 'anchor',
              border: false,
              columnWidth: .49,
              items: [
                {
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_host'); ?>',
                  value: '<?php echo $host . ' (' . $ip . ')'; ?>'
                },
                {
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_operating_system'); ?>',
                  value: '<?php echo $system . ' ' . $kernel; ?>'
                },
                {
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_server_date'); ?>',
                  value: '<?php echo $date; ?>'
                }
              ]
            },
            {
              border: false,
              columnWidth: .49,
              items: [
                {
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_database_host'); ?>',
                  value: '<?php echo $db_server . ' (' . $db_ip . ')'; ?>'
                },
                {
                  xtype: 'displayfield',
                  fieldLabel: '<?php echo lang('field_database_version'); ?>',
                  value: '<?php echo $db_version; ?>'
                }
              ]
            }
          ]
        },
        {
          xtype: 'displayfield',
          fieldLabel: '<?php echo lang('field_http_server'); ?>',
          value: '<?php echo $http_server; ?>',
          group: true
        },
        {
          xtype: 'displayfield',
          fieldLabel: '<?php echo lang('field_php_version'); ?>',
          value: '<?php echo 'PHP: ' . $php . ' / Zend: ' . $zend; ?>'
        }
      ]
    });
    
    return frmServerInfo;
  }
})

/* End of file server_info_dialog.php */
/* Location: ./system/modules/server_info/views/server_info_dialog.php */
