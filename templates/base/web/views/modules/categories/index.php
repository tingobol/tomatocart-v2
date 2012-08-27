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

<div id="boxCategories" class="box">
    <h4><?php echo lang('box_categories_heading'); ?></h4>
    
    <div class="contents">
        <ul>
        <?php 
          foreach ($categories as $category) :
        ?>
        	<li><a href="<?php echo site_url('cpath/' . $category['id']); ?>"><?php echo $category['title']; ?></a></li>
        <?php 
          endforeach;
        ?>
        </ul>
    </div>
</div>