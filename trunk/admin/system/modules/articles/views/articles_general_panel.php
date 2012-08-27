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
 * @filesource modules/articles/views/articles_general_panel.php
 */
?>

Ext.define('Toc.articles.GeneralPanel', {
  extend: 'Ext.tab.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_general'); ?>';
    config.activeTab = 0;
    config.border = false;
    config.deferredRender = false;
    
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    <?php
      foreach(lang_get_all() as $l)
      {
    ?>
        var pnlLang<?php echo $l['code']; ?> = Ext.create('Ext.Panel', {
          title: '<?php echo $l['name']; ?>',
          iconCls: 'icon-<?php echo $l['country_iso']; ?>-win',
          border: false,
          layout: 'anchor',
          bodyPadding: 6,
          items: [
            {
              xtype: 'textfield', 
              fieldLabel: '<?= lang('field_article_name'); ?>', 
              name: 'articles_name[<?php echo $l['id']; ?>]', 
              allowBlank: false
            },
            {
              xtype: 'htmleditor',
              fieldLabel: '<?= lang('filed_article_description'); ?>',
              name: 'articles_description[<?php echo $l['id']; ?>]',
              height: 230
            }
          ]
        });
        
        items.push(pnlLang<?php echo $l['code']; ?>);
    <?php
      }
    ?>
    
    return items;
  }
});

/* End of file articles_general_panel.php */
/* Location: ./system/modules/articles/views/articles_general_panel.php */