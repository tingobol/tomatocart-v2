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
    <h4 class="title"><?php echo $title; ?></h4>
    
    <div class="contents">
        <form method="get" action="<?php echo $action;?>" name="search">
        	<input type="text" maxlength="30" style="width: 80%;" name="keywords">&nbsp;
        	<p>
        	  <button class="button small"><?php echo $btn_search_text; ?></button>
        	</p>
        	<a href="<?php echo $advanced_search_link;?>"><?php echo $link_advanced_search_text;?></a>
        </form>
    </div>
</div>