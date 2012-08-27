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
 * @filesource ./system/modules/homepage_info/views/homepage_info_panel.php
 */
?>

Ext.define('Toc.homepage_info.HomepageInfoPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_homepage_text_title'); ?>';
    config.border = false;
    config.layout = 'fit';
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var tabHomepageInfo = Ext.create('Ext.tab.Panel', {
      activeTab: 0,
      border: false,
      deferredRender: false
    });
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          layout: 'anchor',
          bodyPadding: 6,
          border: false,
          items: [
            {xtype: 'htmleditor', height: 300, fieldLabel: '<?= lang('field_homepage_text'); ?>', name: 'index_text[<?php echo $l['id']; ?>]'}
          ]
        });
        
        tabHomepageInfo.add(lang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return tabHomepageInfo;
  }
});

/* End of file homepage_info_panel.php */
/* Location: ./system/modules/homepage_info/views/homepage_info_panel.php */