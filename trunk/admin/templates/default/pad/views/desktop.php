<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

/*
 * Desktop configuration
 */

Ext.onReady(function () {
  var TocDesktop;
  
  TocDesktop = new Toc.desktop.App({
    startConfig: {
      title : "<?php echo $username; ?>"
    },
    
    /**
     * Return modules.
     */
    getModules: function() {
      return <?php echo $modules; ?>;
    },
    
    /**
     * Return the launchers object.
     */
    getLaunchers : function(){
      return <?php echo $launchers; ?>;
    },
    
    /**
     * Return the Styles object.
     */
    getStyles : function(){
      return <?php echo $styles; ?>;
    },
    
    onLogout: function() {
      Ext.Ajax.request({
        url: Toc.CONF.CONN_URL,
        params: {
          action: 'logoff',
        },
        callback: function(options, success, response) {
          result = Ext.decode(response.responseText);
          
          if (result.success == true) {
            window.location = "<?php echo base_url(); ?>";
          }
        }
      });
    },
    
    onSettings: function() {
      var winSetting = this.getDesktopSettingWindow();
      
      winSetting.show();
    }
  });
});

<?php echo $output; ?>
