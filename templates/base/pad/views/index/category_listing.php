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

<div data-role="content">
  <h1><?php echo $title;?></h1>
  
  <div class="categories-list">
    <ul data-role="listview" data-dividertheme="d">
    <?php 
      foreach ($categories as $category) {
    ?>
  		<li><a href="<?php echo site_url('index/' . $category['id']); ?>"><img src="<?php echo image_url('categories/' . $category['info']['image']);?>" /><h3><?php echo $category['info']['name']; ?></h3></a></li>
    <?php 
      }
    ?>
    </ul> 
  </div>
</div>