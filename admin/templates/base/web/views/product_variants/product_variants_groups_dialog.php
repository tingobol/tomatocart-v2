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
 * @filesource ./system/modules/product_variants/views/product_variants_groups_dialog.php
 */
?>

Ext.define('Toc.product_variants.ProductVariantsGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'product_variants_groups-dialog-win';
    config.title = '<?= lang("action_heading_new_variant_group"); ?>';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-product_variants-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
        }, 
        scope: this
      }, 
      {
        text: TocLanguage.btnClose,
        handler: function () {
          this.close();
        }, 
        scope: this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var groupsId = id || null;
    
    if (groupsId > 0) {
      this.frmProductVariantGroup.form.baseParams['products_variants_groups_id'] = groupsId;
      
      this.frmProductVariantGroup.load({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'product_variants',
          action: 'load_product_variant'
        },
        success: function (form, action) {
          Toc.product_variants.ProductVariantsGroupsDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmProductVariantGroup = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'product_variants',
        action: 'save_product_variant'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: ''
      }
    });
    
    <?php
      $i = 1;
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['id']; ?> = Ext.create('Ext.form.TextField', {
          name: 'products_variants_groups_name[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_group_name'); ?>',
          allowBlank: false,
          labelStyle: '<?= worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmProductVariantGroup.add(lang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    return this.frmProductVariantGroup;
  },
  
  submitForm: function () {
    this.frmProductVariantGroup.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function (form, action) {
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },
      failure: function (form, action) {
        if (action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });
  }
});

/* End of file product_variants_groups_dialog.php */
/* Location: ./system/modules/product_variants/product_variants_groups_dialog.php */
