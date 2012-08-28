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

<h1><?php echo $articles_categories_name; ?></h1>

<?php
    if (!empty($articles)) :
        foreach ($articles as $article) :
?>
<div class="moduleBox">
    <h6><span class="fr"><?php echo mdate('%d/%m/%Y', human_to_unix($article['articles_date_added'])); ?></span><a href="<?php echo site_url('articles/' . $article['articles_id']); ?>"><?php echo $article['articles_name']; ?></a></h6>
    
    <div class="content clearfix">
        <p>
            <?php
                $description = strip_tags($article['articles_description']);
                
                echo substr($description, 0, 100) . ((strlen($description) >= 100) ? '..' : ''); 
            ?>
        </p>
    </div>
</div>
<?php
      endforeach;
    else:
?>

<div class="moduleBox">
    <div class="content"><?php echo lang('no_article_in_this_category'); ?></div>
</div>

<div class="submitFormButtons clearfix">
    <a href="<?php echo site_url('index'); ?>" class="button fr"><?php echo lang('button_continue'); ?></a>
</div>

<?php    
    endif;
?>
