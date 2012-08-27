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
 * @filesource modules/weight_classes/views/weight_classes_dialog.php
 */
?>

Ext.define('Toc.weight_classes.WeightClassesDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'weight_classes-dialog-win';
    config.title = '<?= lang("action_heading_new_weight_class"); ?>';
    config.layout = 'fit';
    config.width = 480;
    config.height = 360;
    config.modal = true;
    config.iconCls = 'icon-weight_classes-win';
    
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
    
    this.addEvents({ 'saveSsuccess': true });
    
    this.callParent([config]);
  },
  
  show: function (id) {
    var weightClassesId = id || null;
    
    this.frmWeightClass.form.baseParams['weight_class_id'] = weightClassesId;
    
    if (weightClassesId > 0) {
      this.frmWeightClass.load({
        url: Toc.CONF.CONN_URL,
        params: {
          action: 'load_weight_classes',
          weight_class_id: weightClassesId
        },
        success: function (form, action) {
          var rules = action.result.data.rules;
          
          Ext.each(rules, function(rule) {
            this.frmWeightClass.add({
              xtype: 'numberfield',
              name: 'rules[' + rule.weight_class_id + ']',
              fieldLabel: rule.weight_class_title,
              value: rule.weight_class_rule
            });
          }, this);
          
          if (!action.result.data.is_default) {    
            this.frmWeightClass.add({
              xtype: 'checkbox',
              name: 'is_default',
              fieldLabel: '<?= lang("field_set_as_default"); ?>'
            });
          }
          
          this.doLayout();
          
          Toc.weight_classes.WeightClassesDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      Ext.Ajax.request({
        url: Toc.CONF.CONN_URL,
        params: {
          module: 'weight_classes',
          action: 'get_weight_classes_rules'
        },
        callback: function(options, success, response) {
          var result = Ext.decode(response.responseText);
          
          if (result.success) {
            var rules = result.rules;
            
            Ext.each(rules, function(rule) {
              this.frmWeightClass.add({
                xtype: 'numberfield',
                name: 'rules[' + rule.weight_class_id + ']',
                fieldLabel: rule.weight_class_title,
                value: rule.weight_class_rule
              });
            }, this);
          
            this.frmWeightClass.add({
              xtype: 'checkbox',
              name: 'is_default',
              fieldLabel: '<?= lang("field_set_as_default"); ?>',
              anchor: ''
            });
          
            this.doLayout();
          }
          
          Toc.weight_classes.WeightClassesDialog.superclass.show.call(this);
        },
        scope: this
      });
    }
  },
  
  buildForm: function () {
    this.frmWeightClass = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'weight_classes',
        action: 'save_weight_classes'       
      },
      border: false,
      bodyPadding: 10,
      autoScroll: true,
      fieldDefaults: {
        anchor: '95%',
        labelSeparator: ''
      }
    });
    
    <?php
      $i = 1;
      
      foreach(lang_get_all() as $l)
      {
    ?>
        var lang<?php echo $l['id']; ?> = Ext.create('Ext.Panel', {
          id: 'la<?php echo $i; ?>',
          layout: 'column',
          border: false,
          items: [
            {
              width: 210,
              layout: 'anchor',
              border: false,
              items: [
                {
                  xtype: 'textfield',
                  name: 'name[<?php echo $l['id']; ?>]',
                  labelStyle: '<?= worldflag_url($l['country_iso']); ?>',
                  width: 100,
                  allowBlank: false,
                  fieldLabel: '<?php echo $i == 1 ? lang('field_title_and_code') : '&nbsp;'?>'
                }
              ]
            },
            {
              layout: 'anchor',
              border: false,
              items: [
                {
                  xtype: 'textfield',
                  name: 'key[<?php echo $l['id']; ?>]',
                  width: 100,
                  allowBlank: false,
                  hideLabel: true
                }
              ]
            }
          ]
        });
        
        this.frmWeightClass.add(lang<?php echo $l['id']; ?>);
    <?php
        $i++;
      }
    ?>
    
    this.frmWeightClass.add({
      xtype: 'displayfield',
      border: false,
      fieldLabel: '<?= lang("field_rules"); ?>',
      value: ''
    });
    
    return this.frmWeightClass;
  },
  
  submitForm: function () {
    this.frmWeightClass.form.submit({
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



/* End of file weight_classes_dialog.php */
/* Location: ./system/modules/weight_classes/views/weight_classes_dialog.php */