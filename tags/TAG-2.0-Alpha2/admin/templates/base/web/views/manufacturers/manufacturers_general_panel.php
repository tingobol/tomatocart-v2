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
 * @filesource
 */
?>

Ext.define('Toc.manufacturers.GeneralPanel', {
  extend: 'Ext.Panel',
  
  constructor: function(config) {
    config = config || {};
    
    config.title = '<?= lang('section_general'); ?>';
    config.border = false;
    config.layout = 'anchor';
    config.bodyPadding = 8;
    config.items = this.buildForm();
      
    this.callParent([config]);
  },
  
  buildForm: function() {
    var items = [];
    
    items.push({xtype: 'textfield', fieldLabel: '<?= lang('field_name'); ?>', name: 'manufacturers_name', allowBlank: false});
    items.push({xtype: 'panel', id: 'manufactuerer_image_panel', border: false, html: ''});
    items.push({xtype: 'fileuploadfield', fieldLabel: '<?= lang('field_image'); ?>', name: 'manufacturers_image'});
    
    <?php
      $i = 1;
      foreach(lang_get_all() as $l)
      {
    ?>
        this.lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'manufacturers_url[' + '<?php echo $l['id']; ?>' + ']',
          fieldLabel: '<?php echo $i == 1 ? lang('field_url') : '&nbsp;'; ?>',
          labelStyle: '<?= worldflag_url($l['country_iso']); ?>',
          value: 'http://'
        });
        
        items.push(this.lang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }  
    ?>
    
    return items;
  }
});

/* End of file manufacturers_general_panel.php */
/* Location: ./system/modules/manufacturers/views/manufacturers_general_panel.php */