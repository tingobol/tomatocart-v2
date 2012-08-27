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
 * @filesource modules/articles_categories/views/articles_categories_general_panel.php
 */
?>

Ext.define('Toc.articles_categories.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_general'); ?>';
    config.bodyPadding = 8;
    config.border = false;
    config.layout = 'anchor';
    config.items = this.buildForm();
    
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    <?php
      $i = 1;
      
      foreach(lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id'] ?> = Ext.create('Ext.form.TextField', {
          name: 'articles_categories_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_name') ?>',
          allowBlank: false,
          labelStyle: '<?= worldflag_url($l['country_iso']); ?>'
        });
        
        items.push(txtLang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    var pnlPublish = {
      layout: 'column',
      border: false,
      items: [
        {
          border: false,
          items: [
            {
              xtype: 'radio', 
              name: 'articles_categories_status', 
              fieldLabel: '<?= lang('field_publish'); ?>', 
              inputValue: '1', 
              boxLabel: '<?= lang('field_publish_yes'); ?>', 
              checked: true
            }
          ]
        },
        {
          border: false,
          style: 'padding-left: 5px;',
          items: [
            {
              xtype: 'radio', 
              hideLabel: true, 
              name: 'articles_categories_status', 
              inputValue: '0',
              boxLabel: '<?= lang('field_publish_no'); ?>'
            }
          ]
        }
      ]
    };
    
    items.push(pnlPublish);
    
    items.push({xtype: 'numberfield', id: 'articles_categories_order', name: 'articles_categories_order', fieldLabel: '<?= lang('field_articles_order'); ?>', allowBlank: false});
    
    return items;
  }
});

/* End of file articles_categories_general_panel.php */
/* Location: ./system/modules/articles_categories/articles_categories_general_panel.php */