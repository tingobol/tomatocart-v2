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
 * @filesource .system/modules/reviews/views/reviews_edit_dialog.php
 */
?>

Ext.define('Toc.reviews.ReviewsEditDialog', {
  extend: 'Ext.Window',
  
  constructor: function(config) {
    config = config || {};
    
    config.id = 'reviews-dialog-win';
    config.title = '<?= lang("action_heading_new_special"); ?>';
    config.layout = 'fit';
    config.width = 525;
    config.autoHeight = true;
    config.modal = true;
    config.iconCls = 'icon-reviews-win';
    
    config.items = this.buildForm();
    
    config.buttons = [
      {
        text: TocLanguage.btnSave,
        handler: function () {
          this.submitForm();
          this.disable();
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
    var reviewsId = id || null;
    
    if (reviewsId > 0) {
      this.frmReviews.form.baseParams['reviews_id'] = reviewsId;
      
      this.frmReviews.load({
        url: Toc.CONF.CONN_URL,
        params: {
          action: 'load_reviews'
        },
        success: function (form, action) {
          if ( Ext.isEmpty(action.result.data.ratings) ) {
            this.pnlAverageRating = Ext.create('Ext.Panel', {
              layout: {
                type: 'table',
                columns: 8
              },
              border: false,
              items: [
                {xtype: 'label', text: '<?= lang("field_detailed_rating"); ?>'},
                {xtype: 'label', text: '<?= lang("rating_bad"); ?>', style: 'padding-left: 70px;padding-right: 10px;'}, 
                {xtype: 'radio', name: 'detailed_rating', inputValue: '1', checked: action.result.data.detailed_rating == 1},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '2', checked: action.result.data.detailed_rating == 2},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '3', checked: action.result.data.detailed_rating == 3},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '4', checked: action.result.data.detailed_rating == 4},
                {xtype: 'radio', name: 'detailed_rating', inputValue: '5', checked: action.result.data.detailed_rating == 5},
                {xtype: 'label', text: '<?= lang("rating_good"); ?>', style: 'padding-left: 10px;'}
              ]
            });
            
            this.frmReviews.add(this.pnlAverageRating);   
          } else {
            var items = [];
            for (var i = 0; i < action.result.data.ratings.length; i++){
              var n = action.result.data.ratings[i].customers_ratings_id;
              var name = "ratings_value" + n;
              
              items.push({xtype: 'displayfield', value: action.result.data.ratings[i].name});
              items.push({xtype: 'displayfield', value: '<?= lang("rating_bad"); ?>'}); 
              items.push({xtype: 'radio', name: name, inputValue: '1', checked: action.result.data.ratings[i].value == 1, style: 'padding-left: 10px'});
              items.push({xtype: 'radio', name: name, inputValue: '2', checked: action.result.data.ratings[i].value == 2, style: 'padding-left: 10px'});
              items.push({xtype: 'radio', name: name, inputValue: '3', checked: action.result.data.ratings[i].value == 3, style: 'padding-left: 10px'});
              items.push({xtype: 'radio', name: name, inputValue: '4', checked: action.result.data.ratings[i].value == 4, style: 'padding-left: 10px'});
              items.push({xtype: 'radio', name: name, inputValue: '5', checked: action.result.data.ratings[i].value == 5, style: 'padding-left: 10px'});
              items.push({xtype: 'displayfield', value: '<?= lang("rating_good"); ?>'});
            }
            
            var pnlDetailedRatings = Ext.create('Ext.Panel', {
              layout: {
                type: 'table',
                columns: 8
              },
              border: false,
              defaultType: 'radio',
              items: items
            });
            
            this.frmReviews.add(pnlDetailedRatings);
          }
          
          this.frmReviews.add(this.getPnlStatus(action.result.data.reviews_status));
          this.frmReviews.add(this.txtRating);
          this.frmReviews.form.setValues(action.result.data);
          
          Toc.reviews.ReviewsEditDialog.superclass.show.call(this);
        },
        failure: function (form, action) {
          Ext.Msg.alert(TocLanguage.msgErrTitle, action.result.feedback);
        },
        scope: this
      });
    } else {
      this.callParent();
    }
  },
  
  buildForm: function() {
    this.frmReviews = Ext.create('Ext.form.Panel', {
      url: Toc.CONF.CONN_URL,
      baseParams: {
        module: 'reviews',
        action: 'save_reviews'
      },
      border: false,
      bodyPadding: 10,
      fieldDefaults: {
        labelWidth: 150,
        anchor: '97%',
        labelSeparator: '' 
      },
      items: [
        {xtype: 'displayfield', fieldLabel: '<?= lang("field_product"); ?>', name: 'products_name'},
        {xtype: 'displayfield', fieldLabel: '<?= lang("field_author"); ?>', name: 'customers_name'},
        {xtype: 'displayfield', fieldLabel: '<?= lang("field_summary_rating"); ?>', name: 'reviews_rating'}
      ]
    });
    
    this.txtRating = {xtype: 'textarea', fieldLabel: '<?= lang("field_review"); ?>', name: 'reviews_text', height: 150, allowBlank: false};
    
    return this.frmReviews;
  },
  
  getPnlStatus: function(status) {
    return Ext.create('Ext.Panel', {
      layout: 'column',
      border: false,
      items: [
        {
          layout: 'anchor',
          style: 'padding-right: 10px;',
          border: false,
          items: [
            {
              xtype: 'radio', 
              name: 'reviews_status', 
              fieldLabel: '<?= lang('field_review_status'); ?>', 
              inputValue: '1', 
              boxLabel: '<?= lang('field_status_enabled'); ?>', 
              checked: true,
              anchor: '',
              checked: status == 1
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
              name: 'reviews_status', 
              inputValue: '0', 
              boxLabel: '<?= lang('field_status_disabled'); ?>', 
              width: 150,
              checked: status == 0
            }
          ]
        }
      ]
    });
  },
  
  submitForm: function () {
    var fields = this.frmReviews.form.getFieldValues();
    
    this.frmReviews.form.submit({
      waitMsg: TocLanguage.formSubmitWaitMsg,
      params: fields,
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

/* End of file reviews_edit_dialog.php */
/* Location: ./system/modules/reviews/views/reviews_edit_dialog.php */

