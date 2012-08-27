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

<div class="module-box">
    <ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="f">
        <li data-role="list-divider"><?php echo $title; ?></li>
      <?php 
        foreach ($informations as $info):
      ?>
        <li><a href="<?php echo $info['link_href']; ?>"><?php echo $info['link_title']; ?></a></li>
      <?php 
        endforeach;
      ?>
    	<li><a href="<?php echo $contact_link; ?>"><?php echo $contact_link_title; ?></a></li>
    	<li><a href="<?php echo $sitemap_link; ?>"><?php echo $sitemap_link_title; ?></a></li>
    </ul>
</div>