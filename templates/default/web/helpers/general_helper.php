<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
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

/**
 * Build categories dropdown menu
 * 
 * @access public
 * @param $categories 
 * @param $data
 * @param $level
 * @param $parents_id
 * @return string
 */
function build_categories_dropdown_menu($parents_id = 0, $categories = null, $data = null, $level = 0) 
{
    //if it is top category
    if ($parents_id == 0) 
    {
        //get ci instance
        $ci = get_instance();
        
        $data = $ci->category_tree->get_data();
        $categories = $data[0];
        $result = '<ul role="navigation" class="nav">';
    }
    else
    {
        $result = '<ul role="menu" class="dropdown-menu" aria-labelledby="drop' . $parents_id . '">';
    }
    
    if (is_array($categories) && !empty($categories)) 
    {
        foreach ($categories as $categories_id => $categories) 
        {
            $has_sub_category = in_array($categories_id, array_keys($data));
            $name = ($has_sub_category == TRUE) ? $categories['name']  . '&nbsp;&nbsp;<b class="caret"></b>' : $categories['name'];
            $link_attributes = ($has_sub_category == TRUE) ? 'data-toggle="dropdown" class="dropdown-toggle" role="button" id="drop' . $categories_id . '"' : '';
            
            $result .= ($parents_id == 0) ? '<li class="dropdown">' : '<li class="dropdown" role="menuitem">';
            $result .= anchor(site_url('cpath/' . $categories_id), $name, $link_attributes);
            
            if($has_sub_category) 
            {
                $result .= build_categories_dropdown_menu($categories_id, $data[$categories_id], $data, $level + 1);
            }
            
            $result .=  '</li>';
        }
    }
    
    $result .= '</ul>';
    
    return $result;
}

/**
 * Get shopping cart contents
 * 
 * @access public
 * @return string
 */
function get_shopping_cart_contents()
{
    //get ci instance
    $ci = get_instance();
    $contents = $ci->shopping_cart->get_contents();
    
    $html = '<ul class="popup-cart-contents">';
    
    //contents
    foreach ($contents as $content) 
    {
        $html .= '<li>' . $content['quantity'] . ' x ' . $content['name'] . '</li>';
    }
    
    //totals
    $total = $ci->shopping_cart->get_order_totals('total');
    if ($total != NULL) {
        $html .= '<li class="divider"></li><li class="pull-right">' . $total['title'] . $total['text'] . '</li>';
    }
    
    $html .= '</ul>';
    
    return $html;
}

/* End of file helper.php */
/* Location: ./templates/default/web/helpers/helper.php */