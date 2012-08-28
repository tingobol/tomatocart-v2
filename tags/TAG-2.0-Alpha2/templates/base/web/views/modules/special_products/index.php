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
    <h4><a href="<?php echo $special_products_link; ?>"><?php echo $title; ?></a></h4>
    
    <div class="contents">
        <a href="<?php echo $product_link; ?>">
			<img title="<?php echo $product_name; ?>" alt="<?php echo $product_name; ?>" src="<?php echo $product_image; ?>" />
        </a>
        <p>
            <?php echo $product_name; ?><br />
            <s><?php echo $old_product_price; ?></s>&nbsp;<?php echo $product_price; ?>
        </p>
    </div>
</div>