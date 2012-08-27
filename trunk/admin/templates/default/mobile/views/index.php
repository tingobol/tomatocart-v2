<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
  $Id: desktop.php $
  TomatoCart Open Source Shopping Cart Solutions
  http://www.tomatocart.com

  Copyright (c) 2009 Wuxi Elootec Technology Co., Ltd

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

//Set the path for this desktop template
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>templates/default/mobile/javascript/senchatouchchart/resources/css/touch-charts-demo.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>templates/default/mobile/grid/resources/css/Ext.ux.grid.View.css" type="text/css">

<script type="text/javascript">
//<debug>
Ext.Loader.setPath({
    'Ext': base_url + 'templates/default/mobile/javascript/senchatouch2/src',
    'Kitchensink': base_url + 'templates/default/mobile/app',
    'Ext.ux.touch.grid': base_url + 'templates/default/mobile/grid/Ext.ux.touch.grid'
});
//</debug>

/**
 * Ext.application is the heart of your app. It sets the application name, can specify the icon and startup images to
 * use when your app is added to the home screen, and sets up your application's dependencies - usually the models,
 * views and controllers that your app uses.
 */
Ext.application({
    name: 'Kitchensink',

    //sets up the icon and startup screens for when the app is added to a phone/tablet home screen

    glossOnIcon: false,
    icon: {
        57: 'resources/icons/icon.png',
        72: 'resources/icons/icon@72.png',
        114: 'resources/icons/icon@2x.png',
        144: 'resources/icons/icon@114.png'
    },

    phoneStartupScreen: 'resources/loading/Homescreen.jpg',
    tabletStartupScreen: 'resources/loading/Homescreen~ipad.jpg',

    //loads app/store/Demos.js, which contains the tree data for our main navigation NestedList
    stores: ['Demos'],

    //the Kitchen Sink has Phone and Tablet modes, which rearrange the screen based on the type
    //of device detected
    profiles: ['Tablet']
});

</script>
<script src="<?php echo base_url(); ?>templates/default/mobile/javascript/senchatouchchart/touch-charts.js"></script>