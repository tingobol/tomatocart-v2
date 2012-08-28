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

<!DOCTYPE HTML>
<html manifest="" lang="en-US">
<head>
    <title><?php echo lang('administration_title'); ?></title>
    <meta charset="UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <link rel="shortcut icon" href="<?php echo base_url();?>images/tomatocart.ico" type="image/x-icon" />
    <?php echo $template['meta_tags'];?>

    <link rel="stylesheet" href="<?php echo base_url(); ?>templates/default/mobile/javascript/senchatouch2/resources/css/sencha-touch.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>templates/default/mobile/resources/css/app.css">
    <?php echo $template['stylesheets'];?>

	<script type="text/javascript">
		var base_url = "<?php echo base_url(); ?>";
	</script>
    <script src="<?php echo base_url(); ?>templates/default/mobile/javascript/senchatouch2/sencha-touch-all-debug.js"></script>
    <?php echo $template['javascripts'];?>
</head>

<body>
    <?php 
      echo $template['body'];
    ?>
</body>
</html>