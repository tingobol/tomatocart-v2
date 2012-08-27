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

<div class="box">
    <h4 class="title"><a href="<?php echo $new_products_link; ?>"><?php echo $title; ?></a></h4>
    
    <div class="contents">
        <a href="<?php echo $product_link; ?>">
			<img border="0" title="<?php echo $product_name; ?>" alt="<?php echo $product_name; ?>" src="<?php echo $product_image; ?>">
        </a>
        <div><?php echo $product_name; ?></div>
        <div><?php echo $product_price; ?></div>
    </div>
</div>