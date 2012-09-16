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
 * @filesource modules/articles_categories/views/articles_categories_meta_info_panel.php
 */
?>

Ext.define('Toc.articles_categories.MetaInfoPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?php echo lang('section_meta'); ?>';
    config.activeTab = 0;
    config.deferredRender = false;
    config.border = false;
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var panels = [];
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso'] ?>-win',
          bodyPadding: 8,
          border: false,
          layout: 'anchor',
          items: [
            {xtype: 'textfield', fieldLabel: '<?php echo lang('field_page_title'); ?>', name: 'page_title[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_keywords'); ?>', name: 'meta_keywords[<?php echo $l['id']; ?>]'},
            {xtype: 'textarea', fieldLabel: '<?php echo lang('field_meta_description'); ?>', name: 'meta_description[<?php echo $l['id']; ?>]'},
            {
              xtype: 'textfield', 
              fieldLabel: '<?php echo lang('field_url'); ?>', 
              name: 'articles_categories_url[<?php echo $l['id']; ?>]',
              lableStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
            }
          ]
        });
        
        panels.push(lang<?php echo $l['code'] ?>);
    <?php
      }
    ?>
    
    return panels;
  }
});

/* End of file articles_categories_meta_info_panel.php */
/* Location: ./system/modules/articles_categories/articles_categories_meta_info_panel.php */