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
    <div id="<?php echo $mid; ?>" class="slides">
        <div class="slides_container">
        <?php 
          foreach($images as $image):
        ?>
            <div class="slide">
            	<a href="<?php echo $image['image_link']; ?>"><img src="<?php echo image_url($image['image_src']); ?>" border="0" alt="<?php echo $image['image_info']; ?>" title="<?php echo $image['image_info']; ?>" /></a>
            </div>
        <?php 
          endforeach;
        ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('#<?php echo $mid; ?>').slides({
            preload: true,
            generatePagination: false,
            play: <?php echo $play_interval; ?>,
            pause: <?php echo $pause_interval; ?>,
            hoverPause: <?php echo $hover_pause; ?>,
            start: 1
        });
    });
</script>