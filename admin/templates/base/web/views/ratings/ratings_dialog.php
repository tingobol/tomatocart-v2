<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package   TomatoCart
 * @author    TomatoCart Dev Team
 * @copyright Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license   http://www.gnu.org/licenses/gpl.html
 * @link    http://tomatocart.com
 * @since   Version 2.0
 * @filesource
 */
?>

Ext.define('Toc.ratings.RatingsDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'ratings-dialog-win';
    config.title = '<?php echo lang('action_heading_new_rating'); ?>';
    config.layout = 'fit';
    config.width = 440;
    config.modal = true;
    config.iconCls = 'icon-ratings-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text:TocLanguage.btnSave,
        handler: function(){
          this.submitForm();
        },
        scope:this
      },
      {
        text: TocLanguage.btnClose,
        handler: function(){
          this.close();
        },
        scope:this
      }
    ];
    
    this.addEvents({'savesuccess': true});
    
    this.callParent([config]);
  },
  
  show: function(id) {
    var ratingsId = id || null;
    
    if (ratingsId > 0) {
      this.frmRatings.form.baseParams['ratings_id'] = ratingsId;
      
      this.frmRatings.load({
        url: '<?php echo site_url('ratings/load_ratings'); ?>',
        success: function(form, action) {
          Toc.ratings.RatingsDialog.superclass.show.call(this);
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
    this.frmRatings = Ext.create('Ext.form.Panel', {
      url: '<?php echo site_url('ratings/save_ratings'); ?>',
      baseParams: {},
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        anchor: '96%',
        labelSeparator: '',
        labelWidth: 120
      } 
    });
    
    <?php
      $i = 1;
      
      foreach (lang_get_all() as $l)
      {
    ?>
        var txtLang<?php echo $l['id'] ?> = Ext.create('Ext.form.TextField', {
          name: 'ratings_text[<?php echo $l['id']; ?>]',
          fieldLabel: '<?php echo $i != 1 ? '&nbsp;' : lang('field_rating_name'); ?>',
          allowBlank: false,
          labelStyle: '<?php echo worldflag_url($l['country_iso']); ?>'
        });
        
        this.frmRatings.add(txtLang<?php echo $l['id']; ?>);
    <?php  
        $i++;
      }
    ?>
    
    var pnlPublish = {
      layout: 'column',
      border: false,
      items: [
        {
          width: 200,
          layout: 'anchor',
          border: false,
          items: [
            {
              xtype: 'radio', 
              name: 'status', 
              fieldLabel: '<?php echo lang('field_rating_status'); ?>', 
              inputValue: '1', 
              boxLabel: '<?php echo lang('field_status_enabled'); ?>', 
              checked: true
            }
          ]
        },
        {
          layout: 'anchor',
          border: false,
          items: [
            {
              xtype: 'radio', 
              hideLabel: true, 
              name: 'status', 
              inputValue: '0', 
              boxLabel: '<?php echo lang('field_status_disabled'); ?>', 
              width: 150
            }
          ]
        }
      ]
    };
    
    this.frmRatings.add(pnlPublish);
    
    return this.frmRatings;
  },
  
  submitForm : function() {
    this.frmRatings.form.submit({
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

/* End of file ratings_dialog.php */
/* Location: ./templates/base/web/views/ratings/ratings_dialog.php */