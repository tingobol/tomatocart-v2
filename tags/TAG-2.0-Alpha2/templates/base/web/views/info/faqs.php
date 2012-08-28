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

<h1><?php echo lang('info_faqs_heading'); ?></h1>

<div id="faqs" class="moduleBox clearfix">
    <div class="content">
      	<?php
            if (!empty($faqs)) :
                foreach($faqs as $faq) :
      	?>
      	<ul>
            <li class="question"><?php echo $faq['faqs_question']?></li>
            <li class="answer hidden"><?php echo $faq['faqs_answer']?></li>
            <?php
                if (!empty($faq['faqs_url'])) :
            ?>
            <li class="url hidden"><a href="<?php echo $faq['faqs_url']; ?>"><?php echo $faq['faqs_url']; ?></a></li>
            <?php
                endif;
            ?>
        </ul>
      	<?php
                endforeach;
            else :
        ?>
        <p><?php echo lang('field_faqs_no_records'); ?></p>
        <?php    
            endif;
      	?>
    </div>
</div>

<div class="submitFormButtons clearfix">
  <a href="<?php echo site_url('index'); ?>" class="button fr"><?php echo lang('button_continue'); ?></a>
</div>

<script language="javascript" type="text/javascript">
    $(document).ready(function(){
    	$('.question').bind('click', function() {
    		  $(this).next('.answer').toggle();
    		  $('#faqs .url').toggle();
    	});
    });
</script>