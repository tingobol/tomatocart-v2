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
 * @filesource system/modules/languages/views/modules_tree_panel.php
 */
?>

Ext.define('Toc.languages.ModulesTreePanel', {
  extend: 'Ext.tree.TreePanel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('tree_head_title'); ?>';
    config.region = 'west';
    config.autoScroll = true;
    config.width = 170;
    config.rootVisible = false;
    config.currentGroup = 'general';
    
    config.store = Ext.create('Ext.data.TreeStore', {
      proxy: {
        type: 'ajax',
        url : Toc.CONF.CONN_URL,
        extraParams: {
          module: 'languages',
          action: 'list_translation_groups',
          languages_id: config.languagesId
        }
      },
      root: {
        id: '0',
        text: '<?php echo lang('heading_languages_title'); ?>',
        leaf: false,
        expandable: true,  
        expanded: true  
      },
      listeners: {
        'load': function() {
          this.setContentGroup('general');
        },
        scope: this
      }
    });
    
    config.listeners = {
      "itemclick": this.onTreeNodeClick
    };
    
    this.addEvents({'selectchange': true});
    
    this.callParent([config]);
  },
  
  onTreeNodeClick: function(view, record) {
    var group = record.get('id');
    
    this.setContentGroup(group);
  },
  
  setContentGroup: function(group) {
    this.currentGroup = group;
    
    this.fireEvent('selectchange', group);
  }
});

/* End of file modules_tree_panel.php */
/* Location: system/modules/languages/views/modules_tree_panel.php */
