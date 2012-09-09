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

<h1>
	<?php echo lang('title');?>
</h1>

<div class="box">
	<?php if (is_array($wishlist_products) && !is_null($wishlist_products)):?>

	<form name="update_wishlist" method="post"
		action="<?php echo site_url($link_wishlist); ?>">

		<table border="0" width="100%" cellspacing="0" cellpadding="10"
			class="productListing">
			<thead>
				<tr>
					<th class="productListing-heading" align="center"><?php echo lang('text_product'); ?>
					</th>
					<th class="productListing-heading"><?php echo lang('text_comments'); ?></th>
					<th class="productListing-heading" align="center" width="70"><?php echo lang('text_date'); ?>
					</th>
					<th class="productListing-heading"></th>
				</tr>
			</thead>
			<tfoot></tfoot>
			<tbody>
				<?php $rows=0; ?>
				<?php foreach ($wishlist_products as $product):?>
				<?php $rows++; ?>
				<tr
					class="<?php echo ((($rows/2) == floor($rows/2)) ? 'productListing-even' : 'productListing-odd'); ?>">
					<td align="center"><a href="<?php echo $product['link'];?>"><img
							src="<?php echo image_url($product['image']); ?>" title="<?php echo $product['name']; ?>"
							alt="<?php echo $product['name']; ?>"> </a><br /> <span><?php echo $product['name']; ?>
					</span><br /> <span><?php echo $product['price']; ?> </span></td>
					<td valign="top"><textarea id="comments_<?php echo $product['id'];?>"
							rows="5" cols="20" name="comments[<?php echo $product['id'];?>]"></textarea>
					</td>
					<td align="center" valign="top"><?php echo $product['date_added']; ?></td>
					<td align="center" valign="top"><a class="button"
						href="<?php echo site_url($link_delete); ?>"><?php echo lang('text_delete');?> </a> <br />&nbsp;<br />
						<a class="button" href="<?php echo site_url($link_cart); ?>"><?php echo lang('text_cart');?> </a>
					</td>
				</tr>
				<?php endforeach;?>
			</tbody>
		</table>

		<div class="submitFormButtons" style="text-align: right;">
			<a class="button" href="<?php echo site_url($link_update); ?>"><?php echo lang('text_update');?> </a>
			<a class="button" href="<?php echo site_url($link_back); ?>"><?php echo lang('text_back');?> </a>
		</div>
	</form>
	<?php else : ?>
	<div class="content">
		<span><?php echo lang('text_empty'); ?> </span>
	</div>

	<div class="submitFormButtons" style="text-align: right;">
		<a class="button" href="<?php echo site_url($link_update); ?>"><?php echo lang('text_update');?> </a>
		<a class="button" href="<?php echo site_url($link_back); ?>"><?php echo lang('text_back');?> </a>
	</div>
	<?php endif;?>

	<?php if (is_array($wishlist_products) && !is_null($wishlist_products)):?>
	<div class="box">

		<h6>
			<em><?php echo lang('text_required'); ?> </em>
			<?php echo lang('text_share'); ?>
		</h6>

		<form name="share_wishlist" id="share_wishlist" method="post"
			action="<?php echo site_url($link_share); ?>">
			<div class="content">
				<p>
					<label for="wishlist_customer"><?php echo lang('text_name'); ?><em>*</em> </label>
					<input type="text" value="<?php echo set_value($value_name);?>" id="wishlist_customer"
						name="wishlist_customer">
				</p>
				<p>
					<label for="wishlist_from_email"><?php echo lang('text_from_email'); ?><em>*</em>
					</label> <input type="text" value="<?php echo set_value($value_from_email);?>"
						id="wishlist_from_email" name="wishlist_from_email">
				</p>
				<p>
					<label for="wishlist_emails"><?php echo lang('text_emails'); ?><em>*</em> </label>
					<textarea id="wishlist_emails" rows="5" cols="40"
						name="wishlist_emails"></textarea>
				
				
				<p>
					<label for="wishlist_message"><?php echo lang('text_message'); ?><em>*</em> </label>
					<textarea id="wishlist_message" rows="5" cols="40"
						name="wishlist_message"></textarea>
				</p>
			</div>
			<div class="submitFormButtons" style="text-align: right;">
				<a class="button" href="<?php echo site_url($link_continue); ?>"><?php echo lang('text_continue'); ?>
				</a>
			</div>
		</form>
	</div>
	<?php endif;?>
</div>
