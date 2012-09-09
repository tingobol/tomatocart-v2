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
 * @filesource ./system/modules/reviews/views/main.php
 */

  echo 'Ext.namespace("Toc.reviews");';
?>

Ext.override(Toc.desktop.ReviewsWindow, {

  createWindow: function(){
    var desktop = this.app.getDesktop();
    var win = desktop.getWindow('reviews-win');
     
    if(!win){
      var grd = Ext.create(Toc.reviews.ReviewsGrid);
      
      grd.on('edit', function(record) {this.onEditReviews(grd, record);}, this);
      
      grd.on('notifysuccess', this.onShowNotification, this);
      
      win = desktop.createWindow({
        id: 'reviews-win',
        title: '<?php echo lang('heading_title_reviews'); ?>',
        width: 800,
        height: 400,
        iconCls: 'icon-reviews-win',
        layout: 'fit',
        items: grd
      });
    }
    
    win.show();
  },
  
  onEditReviews: function(grd, record) {
    var dlg = this.createReviewsEditDialog();
    dlg.setTitle(record.get('products_name'));
    
    this.onSaveSuccess(dlg, grd);
    
    dlg.show(record.get('reviews_id'));
  },
    
  createReviewsEditDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('reviews-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.reviews.ReviewsEditDialog);
    }
      
    return dlg;
  },
  
  createRatingsDialog: function() {
    var desktop = this.app.getDesktop();
    var dlg = desktop.getWindow('ratings-dialog-win');
    
    if (!dlg) {
      dlg = desktop.createWindow({}, Toc.reviews.RatingsDialog);
      
      dlg.on('saveSuccess', function (feedback) {
        this.app.showNotification({
          title: TocLanguage.msgSuccessTitle,
          html: feedback
        });
      }, this);
    }
      
    return dlg;
  },
  
  onSaveSuccess: function(dlg, grd) {
    dlg.on('savesuccess', function(feedback) {
      this.onShowNotification(feedback);
      
      grd.onRefresh();
    }, this);
  },
  
  onShowNotification: function(feedback) {
    this.app.showNotification({
      title: TocLanguage.msgSuccessTitle,
      html: feedback
    });
  }
});

/* End of file main.php */
/* Location: ./system/modules/reviews/views/main.php */

