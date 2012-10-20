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

<div id="faqs" class="module-box clearfix">
    <div class="content">
      	<?php
            if (!empty($faqs)) :
                foreach($faqs as $faq) :
      	?>
      	<dt>
            <dt class="question"><?php echo $faq['faqs_question']?></dt>
            <dd class="answer<?php echo (isset($active) && ($active == $faq['faqs_id'])) ? '' : ' hidden'; ?>"><?php echo $faq['faqs_answer']?></dd>
            <?php
                if (!empty($faq['faqs_url'])) :
            ?>
            <dd class="url<?php echo (isset($active) && ($active == $faq['faqs_id'])) ? '' : ' hidden'; ?>"><a href="<?php echo $faq['faqs_url']; ?>"><?php echo $faq['faqs_url']; ?></a></dd>
            <?php
                endif;
            ?>
        </dt>
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

<div class="controls clearfix">
	<a href="<?php echo site_url(); ?>" class="btn btn-small btn-info pull-right"><i class="icon-chevron-right icon-white"></i><?php echo lang('button_continue'); ?></a>
</div>

<script language="javascript" type="text/javascript">
    $(document).ready(function(){
    	$('.question').bind('click', function() {
    		  $(this).next('.answer').toggle();
    		  $(this).next('.answer').next('.url').toggle();
    	});
    });
</script>