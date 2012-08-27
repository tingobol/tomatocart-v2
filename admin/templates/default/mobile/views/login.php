<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package      TomatoCart
 * @author       TomatoCart Dev Team
 * @copyright    Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html
 * @link         http://tomatocart.com
 * @since        Version 2.0
 * @filesource
*/
?>

<script type="text/javascript">
Ext.namespace("Toc");
Toc.Languages = [];
<?php 
  foreach (lang_get_all() as $l) {
    echo 'Toc.Languages.push({"id" : "' . $l['code'] . '", "text" : "' . $l['name'] . '"});';
  }
?>

//We've added a third and final item to our tab panel - scroll down to see it
Ext.application({
    name : 'TomatoCart',

    launch : function() {
        var store = Ext.create('Ext.data.Store', {
            fields: ['id', 'text'],
            data: Toc.Languages
        });
        
        var formPanel = Ext.create('Ext.form.Panel', {
            fullscreen : true,
            title : 'TomatoCart-Login',
            iconCls : 'user',
            layout : 'vbox',
            items : [ 
                {
                    xtype : 'panel',
                    height : 200,
                    html : 
                        '<p align="center"><img title="TomatoCart" src="' + base_url + '/assets/images/logo.png" /></p>' +
                        '<p align="center" style="margin-top: 30px; font-size: 150%; color: #555; font-weight: bold; text-shadow: -1px 1px 1px rgba(0, 0, 0, 0.2);">The professional and innovative open source online shopping cart solution</p>'
                },           
                {
                    xtype : 'fieldset',
                    items : [
                        {
                            xtype: 'selectfield',
                            label: '<?php echo lang("field_language"); ?>',
                            store: store,
                            displayField: 'text',
                            valueField: 'id',
                            value: '<?php echo lang_get_code(); ?>',
                            listeners: {
                                change: function(select, value) {
                                    document.location = '<?php site_url('index'); ?>?admin_language=' + value;
                                }
                            }
                        },
                        {
                            xtype : 'textfield',
                            name : 'user_name',
                            label : '<?php echo lang("field_username"); ?>'
                        }, 
                        {
                            xtype : 'passwordfield',
                            name : 'user_password',
                            label : '<?php echo lang("field_password"); ?>'
                        } 
                    ]
                }, 
                {
                    xtype : 'button',
                    text : '<?php echo lang("button_login"); ?>',
                    ui : 'confirm',
                    handler : function() {
                        this.up('formpanel').submit({
                            url: base_url + 'index.php/login/process',
                            success: function (form, result) {
                                window.location = '<?= site_url('index'); ?>';
                              },
                              failure: function (form, result) {
                                  Ext.Msg.alert(result.error, result.error);
                              },
                              scope: this
                        });
                    }
                }, 
                {
					xtype: 'panel',
					height: 15
                },
                {
                    xtype : 'button',
                    text : '<?php echo lang("label_forget_password"); ?>',
                    handler : function() {
                        Ext.Msg.prompt('<?= lang('label_forget_password'); ?>', '<?= lang("ms_forget_password_text"); ?>', function(btn, email){
                            if (btn = 'ok' && !Ext.isEmpty(email)) {
                                Ext.Viewport.mask();
                              
                              Ext.Ajax.request({
                                  url: '<?= site_url('login/get_password'); ?>',
                                  params: {
                                      email_address: email
                                  },
                                  callback: function(options, success, response) {
                                      Ext.Viewport.unmask();
                                
                                      result = Ext.decode(response.responseText);
                                  
                                      Ext.Msg.alert('<?= lang('ms_feedback'); ?>', result.feedback);
                                  },
                                  scope: this
                              }); 
                            }
                        });
                    }
                }
            ]
        });
    }
});
</script>