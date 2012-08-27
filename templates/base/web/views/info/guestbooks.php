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

<h1><?php echo lang('guestbook_heading'); ?></h1>

<?php
    echo toc_validation_errors('guestbook');
?>

<div class="moduleBox">
    <h6 class="title"><?php echo lang('guestbook_heading'); ?></h6>
    
    <div class="content">
        <?php 
            if (!empty($guestbooks)) : 
        ?>
        <dl>
            <?php 
                foreach($guestbooks as $index => $guestbook) : 
            ?>
            <dt>
                <span><?php echo  mdate('%Y/%m/%d - %h:%i %a', human_to_unix($guestbook['date_added'])); ?></span>
                <span><?php echo  $guestbook['title']; ?></span>
            </dt>
            <?php 
                if ($index == (count($guestbooks) - 1)) : 
            ?>
            	<dd class="last"><?php echo $guestbook['content']; ?></dd>
            <?php 
                else : 
            ?>
            	<dd><?php echo $guestbook['content']; ?></dd>
            <?php 
                endif; 
            ?>    
            
            <?php 
                endforeach; 
            ?>
        </dl>
        
        <?php 
            else : 
        ?>
        	<p><?php echo lang('field_guestbook_no_records'); ?></p>
        <?php 
            endif; 
        ?>
        
        <p align="right">
        	<a href="<?php echo site_url('info/guestbooks/add'); ?>" class="button"><?php echo lang('button_write_message'); ?></a>
        </p>
    </div>
</div>
