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
 * @filesource ./system/modules/image_groups/views/image_groups_dialog.php
 */
?>

Ext.define('Toc.image_groups.ImageGroupsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'image_groups-dialog-win';
    config.title = '<?= lang('action_heading_new_image_group'); ?>';
    config.layout = 'fit';
    config.width = 450;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-image_groups-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function() {
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function() {
          this.close();
        },
        scope:this
      }
    ];
    
    this.callParent([config]);
  },
  
  show: function(id) {
    imageGroupsId = id || null;      
    
    this.frmImageGroup.form.baseParams['image_groups_id'] = imageGroupsId;

    if (imageGroupsId > 0) {
      this.frmImageGroup.load({
        url: Toc.CONF.CONN_URL,
        params:{
          action: 'load_image_group'
        },
        success: function(form, action) {
          if(action.result.data.is_default) {
            Ext.getCmp('default_image_group').disable();
          }
            
          Toc.image_groups.ImageGroupsDialog.superclass.show.call(this);
        },
        failure: function(form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, TocLanguage.msgErrLoadData);
        },
        scope: this       
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmImageGroup = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {  
        module: 'image_groups',
        action: 'save_image_group'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '97%',
        labelSeparator: '',
        labelWidth: 150
      },
      items: [
        <?php
          $i = 1;
          foreach(lang_get_all() as $l)
          {
        ?>
            {
              xtype: 'textfield', 
              name: 'title[<?php echo $l['id']; ?>]', 
              fieldLabel: '<?= ($i != 1) ? '&nbsp;' : lang('field_title') ?>',
              labelStyle: '<?= worldflag_url($l['country_iso']); ?>'
            },
        <?php
            $i++;
          }
        ?>
        {xtype: 'textfield', name: 'code', allowBlank: false, fieldLabel: '<?= lang('field_code'); ?>'},
        {xtype: 'numberfield', name: 'size_width', allowBlank: false, fieldLabel: '<?= lang('field_width'); ?>'},
        {xtype: 'numberfield', name: 'size_height', allowBlank: false, fieldLabel: '<?= lang('field_height'); ?>'},
        {xtype: 'checkbox', name: 'force_size', fieldLabel: '<?= lang('field_force_size'); ?>', anchor: ''},
        {xtype: 'checkbox', name: 'is_default', id: 'default_image_group', fieldLabel: '<?= lang('field_set_as_default'); ?>', anchor: ''}
      ]
    });
    
    return this.frmImageGroup;
  },
  
  submitForm: function() {
    this.frmImageGroup.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      success: function(form, action){
        this.fireEvent('savesuccess', action.result.feedback);
        this.close();
      },    
      failure: function(form, action) {
        if(action.failureType != 'client') {
          Ext.MessageBox.alert(TocLanguage.msgErrTitle, action.result.feedback);
        }
      },
      scope: this
    });   
  }
});


/* End of file image_groups_grid.php */
/* Location: ./system/modules/image_groups/views/image_groups_dialog.php */
