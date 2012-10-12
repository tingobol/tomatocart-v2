<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * TomatoCart Open Source Shopping Cart Solution
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License v3 (2007)
 * as published by the Free Software Foundation.
 *
 * @package		TomatoCart
 * @author		TomatoCart Dev Team
 * @copyright	Copyright (c) 2009 - 2012, TomatoCart. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://tomatocart.com
 * @since		Version 2.0
 * @filesource
*/
?>

<h1><?php echo $title; ?></h1>

<?php 
    if (count($categories) > 0):
?>
    <div class="col3 clearfix">
        <?php 
            foreach($categories as $category) :
        ?>
        <div class="pull-left center">
            <a href="<?php echo site_url('cpath/' . $category['id']); ?>">
                <img src="<?php echo image_url('categories/' . $category['info']['image']); ?>" alt="<?php echo $category['info']['name']; ?>" title="<?php echo $category['info']['name']; ?>" />
                <br/>
                <span><?php echo $category['info']['name']; ?></span>
            </a>
        </div>
        <?php 
            endforeach; 
        ?>
    </div
<?php 
    else:
?>
	<p><?php echo lang('no_products_in_category'); ?></p>
<?php 
    endif;
?>